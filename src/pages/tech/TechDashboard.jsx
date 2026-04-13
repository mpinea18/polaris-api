import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { warrantiesAPI, appointmentsAPI, dronesAPI, droneHistoryAPI } from '../../services/api';

const APPT_STATUS = {
  pending:     { l:'Pendiente',   c:'#F59E0B' },
  confirmed:   { l:'Confirmada',  c:'#10B981' },
  in_progress: { l:'En proceso',  c:'#0EA5E9' },
  done:        { l:'Completada',  c:'#64748B' },
};

const WARRANTY_STATUS = {
  pendiente:  { l:'Pendiente',   c:'#F59E0B' },
  aprobada:   { l:'Aprobada',    c:'#10B981' },
  negada:     { l:'Negada',      c:'#EF4444' },
  en_proceso: { l:'En proceso',  c:'#0EA5E9' },
  completada: { l:'Completada',  c:'#64748B' },
};

export default function TechDashboard() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [tab, setTab]               = useState('appointments');
  const [appointments, setAppointments] = useState([]);
  const [warranties, setWarranties]     = useState([]);
  const [drones, setDrones]             = useState([]);
  const [droneHistory, setDroneHistory] = useState([]);
  const [savedMsg, setSavedMsg]         = useState('');
  const [loadingAppts, setLoadingAppts] = useState(false);

  // Modales garantías
  const [warrantyDetail, setWarrantyDetail] = useState(null);
  const [warrantyClose, setWarrantyClose]   = useState(null);
  const [intervencion, setIntervencion]     = useState({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' });

  // Modal cita — registrar intervención
  const [apptClose, setApptClose]   = useState(null);
  const [apptInter, setApptInter]   = useState({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' });

  // Modal historial dron
  const [droneSelected, setDroneSelected] = useState(null);
  const [droneHistDetail, setDroneHistDetail] = useState([]);
  const [loadingHist, setLoadingHist] = useState(false);

  const loadAppointments = () => {
    setLoadingAppts(true);
    appointmentsAPI.getAll()
      .then(data => setAppointments(data.filter(a => a.tecnico_id === user.id || a.tech?.id === user.id)))
      .catch(console.error)
      .finally(() => setLoadingAppts(false));
  };

  const loadWarranties = () =>
    warrantiesAPI.getAll()
      .then(data => setWarranties(data.filter(w => w.tech_id === user.id || w.tech?.id === user.id)))
      .catch(console.error);

  const loadDrones = () =>
    dronesAPI.getAll().then(setDrones).catch(console.error);

  useEffect(() => {
    loadAppointments();
    loadDrones();
  }, []);

  useEffect(() => {
    if (tab === 'warranties') loadWarranties();
  }, [tab]);

  // ── Garantías ──
  const handleUpdateWarrantyStatus = async (id, status) => {
    try { await warrantiesAPI.updateStatus(id, status); loadWarranties(); }
    catch (err) { alert(err.message); }
  };

  const handleCerrarGarantia = async () => {
    if (!intervencion.descripcion) return alert('Describe el trabajo realizado');
    if (!intervencion.resultado)   return alert('Selecciona el resultado final');
    try {
      await droneHistoryAPI.create({
        drone_id:            warrantyClose.drone_id || null,
        tipo:                'garantia',
        descripcion:         intervencion.descripcion,
        partes_reemplazadas: intervencion.partes,
        seriales_nuevos:     intervencion.seriales_nuevos,
        resultado:           intervencion.resultado,
        horas_trabajo:       intervencion.horas_trabajo || null,
        warranty_id:         warrantyClose.id,
      });
      await warrantiesAPI.updateStatus(warrantyClose.id, 'completada');
      setWarrantyClose(null);
      setIntervencion({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' });
      loadWarranties();
      showMsg('Intervención registrada y garantía cerrada correctamente.');
    } catch(err) { alert(err.message); }
  };

  // ── Citas ──
  const handleUpdateApptStatus = async (id, status) => {
    try { await appointmentsAPI.updateStatus(id, status); loadAppointments(); }
    catch (err) { alert(err.message); }
  };

  const handleCerrarCita = async () => {
    if (!apptInter.descripcion) return alert('Describe el trabajo realizado');
    if (!apptInter.resultado)   return alert('Selecciona el resultado final');
    try {
      await droneHistoryAPI.create({
        drone_id:            apptClose.drone_id || apptClose.drone?.id || null,
        tipo:                apptClose.tipo || 'mantenimiento',
        descripcion:         apptInter.descripcion,
        partes_reemplazadas: apptInter.partes,
        seriales_nuevos:     apptInter.seriales_nuevos,
        resultado:           apptInter.resultado,
        horas_trabajo:       apptInter.horas_trabajo || null,
        appointment_id:      apptClose.id,
      });
      await appointmentsAPI.updateStatus(apptClose.id, 'done');
      setApptClose(null);
      setApptInter({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' });
      loadAppointments();
      showMsg('Intervención registrada y cita cerrada correctamente.');
    } catch(err) { alert(err.message); }
  };

  // ── Historial dron ──
  const handleVerHistorial = async (drone) => {
    setDroneSelected(drone);
    setLoadingHist(true);
    try {
      const hist = await droneHistoryAPI.getByDrone(drone.id);
      setDroneHistDetail(hist);
    } catch { setDroneHistDetail([]); }
    setLoadingHist(false);
  };

  const showMsg = (msg) => {
    setSavedMsg(msg);
    setTimeout(() => setSavedMsg(''), 4000);
  };

  const pendingAppts = appointments.filter(a => a.status !== 'done' && a.status !== 'cancelled');
  const doneAppts    = appointments.filter(a => a.status === 'done');
  const pendingWarranties = warranties.filter(w => w.status === 'aprobada' || w.status === 'en_proceso').length;

  const inp = { width:'100%', padding:'10px 12px', background:'#050E1A', border:'1px solid #16334F', borderRadius:6, color:'#fff', fontSize:13, boxSizing:'border-box', fontFamily:"'Courier New',monospace" };

  const RESULTADOS = ['Exitoso','Garantía aprobada y reparada','Garantía negada por daño físico','Reemplazado por unidad nueva','Reparación parcial'];

  const NAV = [
    { k:'appointments', icon:'📅', label:'Mis Citas',    badge: pendingAppts.length },
    { k:'warranties',   icon:'🛡', label:'Garantías',    badge: pendingWarranties },
    { k:'history',      icon:'📋', label:'Historial UAS', badge: 0 },
  ];

  return (
    <div style={{ display:'flex', minHeight:'100vh', background:'#050E1A', fontFamily:"'Courier New', monospace" }}>

      {/* ── Sidebar ── */}
      <aside style={{ width:220, background:'#08172A', borderRight:'1px solid #16334F', display:'flex', flexDirection:'column', flexShrink:0 }}>
        <div style={{ padding:'24px 20px', display:'flex', alignItems:'center', gap:10 }}>
          <span style={{ fontSize:24, color:'#06B6D4' }}>◈</span>
          <div>
            <div style={{ fontWeight:900, fontSize:14, color:'#fff', letterSpacing:3 }}>MMYJ</div>
            <div style={{ fontSize:8, color:'#06B6D4', letterSpacing:4 }}>DRONE SA</div>
          </div>
        </div>
        <div style={{ padding:'12px 20px', borderTop:'1px solid #16334F', borderBottom:'1px solid #16334F', display:'flex', alignItems:'center', gap:10, marginBottom:8 }}>
          <div style={{ width:34, height:34, borderRadius:'50%', background:'#06B6D422', border:'1.5px solid #06B6D4', display:'flex', alignItems:'center', justifyContent:'center', fontWeight:900, fontSize:13, color:'#06B6D4', flexShrink:0 }}>
            {user.name.charAt(0)}
          </div>
          <div>
            <div style={{ fontWeight:700, fontSize:12, color:'#E2E8F0' }}>{user.name}</div>
            <div style={{ fontSize:10, color:'#06B6D4', fontWeight:600 }}>● Técnico</div>
          </div>
        </div>
        <nav style={{ flex:1 }}>
          {NAV.map(item => (
            <button key={item.k} onClick={() => setTab(item.k)}
              style={{ width:'100%', display:'flex', alignItems:'center', justifyContent:'space-between', padding:'11px 20px', border:'none', background:tab===item.k?'#06B6D422':'transparent', color:tab===item.k?'#06B6D4':'#64748B', borderLeft:`3px solid ${tab===item.k?'#06B6D4':'transparent'}`, cursor:'pointer', fontSize:13, fontWeight:600, transition:'all .2s' }}>
              <div style={{ display:'flex', alignItems:'center', gap:10 }}>
                <span>{item.icon}</span> {item.label}
              </div>
              {item.badge > 0 && (
                <span style={{ background:'#F59E0B', color:'#000', borderRadius:'50%', width:18, height:18, fontSize:10, fontWeight:900, display:'flex', alignItems:'center', justifyContent:'center' }}>
                  {item.badge}
                </span>
              )}
            </button>
          ))}
        </nav>
        <button onClick={() => { logout(); navigate('/'); }}
          style={{ margin:'0 16px 16px', background:'transparent', border:'1px solid #16334F', borderRadius:8, padding:10, fontSize:12, color:'#64748B', cursor:'pointer' }}>
          ⏻ Cerrar sesión
        </button>
      </aside>

      {/* ── Main ── */}
      <main style={{ flex:1, padding:'40px 48px', overflowY:'auto' }}>
        {savedMsg && (
          <div style={{ background:'#10B98122', border:'1px solid #10B981', borderRadius:10, padding:'12px 16px', color:'#10B981', fontSize:13, marginBottom:20, fontWeight:600 }}>
            ✓ {savedMsg}
          </div>
        )}

        {/* ── MIS CITAS ── */}
        {tab === 'appointments' && (
          <div>
            <h1 style={{ fontSize:24, fontWeight:900, color:'#E2E8F0', marginBottom:4 }}>Mis Citas Asignadas</h1>
            <p style={{ fontSize:13, color:'#3D5A73', marginBottom:24 }}>{pendingAppts.length} pendientes · {doneAppts.length} completadas</p>

            {loadingAppts ? (
              <div style={{ color:'#64748B', fontSize:13 }}>Cargando citas...</div>
            ) : (
              <>
                {pendingAppts.length > 0 && (
                  <>
                    <div style={{ fontSize:11, fontWeight:700, color:'#64748B', letterSpacing:1, marginBottom:10 }}>PENDIENTES</div>
                    <div style={{ display:'flex', flexDirection:'column', gap:10, marginBottom:24 }}>
                      {pendingAppts.map(a => {
                        const st = APPT_STATUS[a.status] || APPT_STATUS.pending;
                        return (
                          <div key={a.id} style={{ background:'#0C1F38', border:'1px solid #16334F', borderLeft:`3px solid ${st.c}`, borderRadius:12, padding:'16px 18px' }}>
                            <div style={{ display:'flex', justifyContent:'space-between', alignItems:'flex-start', marginBottom:12 }}>
                              <div>
                                <div style={{ fontWeight:700, fontSize:14, color:'#E2E8F0', marginBottom:3 }}>{a.tipo || a.service || 'Cita'}</div>
                                <div style={{ fontSize:12, color:'#94A3B8' }}>👤 {a.user?.name || '—'} · 🚁 {a.drone?.modelo || '—'}</div>
                                <div style={{ fontSize:12, color:'#3D5A73', marginTop:2 }}>📅 {a.fecha || a.date} {a.time || ''}</div>
                              </div>
                              <span style={{ background:st.c+'22', color:st.c, border:`1px solid ${st.c}44`, borderRadius:6, padding:'4px 10px', fontSize:11, fontWeight:700 }}>{st.l}</span>
                            </div>
                            <div style={{ display:'flex', gap:8, flexWrap:'wrap' }}>
                              {a.status === 'confirmed' && (
                                <button onClick={() => handleUpdateApptStatus(a.id, 'in_progress')}
                                  style={{ background:'#0EA5E922', border:'1.5px solid #0EA5E9', borderRadius:7, padding:'7px 14px', fontSize:12, color:'#0EA5E9', cursor:'pointer', fontWeight:700 }}>
                                  ▶ Iniciar atención
                                </button>
                              )}
                              {a.status === 'in_progress' && (
                                <button onClick={() => setApptClose(a)}
                                  style={{ background:'#10B98122', border:'1.5px solid #10B981', borderRadius:7, padding:'7px 14px', fontSize:12, color:'#10B981', cursor:'pointer', fontWeight:700 }}>
                                  ✓ Registrar intervención y cerrar
                                </button>
                              )}
                            </div>
                          </div>
                        );
                      })}
                    </div>
                  </>
                )}

                {doneAppts.length > 0 && (
                  <>
                    <div style={{ fontSize:11, fontWeight:700, color:'#64748B', letterSpacing:1, marginBottom:10 }}>COMPLETADAS</div>
                    <div style={{ display:'flex', flexDirection:'column', gap:8 }}>
                      {doneAppts.map(a => (
                        <div key={a.id} style={{ background:'#0C1F38', border:'1px solid #16334F', borderLeft:'3px solid #64748B', borderRadius:12, padding:'14px 18px', opacity:.7 }}>
                          <div style={{ fontWeight:700, fontSize:13, color:'#64748B', marginBottom:2 }}>{a.tipo || a.service || 'Cita'}</div>
                          <div style={{ fontSize:11, color:'#3D5A73' }}>👤 {a.user?.name || '—'} · 📅 {a.fecha || a.date}</div>
                        </div>
                      ))}
                    </div>
                  </>
                )}

                {appointments.length === 0 && (
                  <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, padding:30, textAlign:'center', color:'#3D5A73' }}>
                    <div style={{ fontSize:32, marginBottom:10 }}>📅</div>
                    <div>No tienes citas asignadas.</div>
                  </div>
                )}
              </>
            )}
          </div>
        )}

        {/* ── GARANTÍAS ── */}
        {tab === 'warranties' && (
          <div>
            <h1 style={{ fontSize:24, fontWeight:900, color:'#E2E8F0', marginBottom:4 }}>Garantías Asignadas</h1>
            <p style={{ fontSize:13, color:'#3D5A73', marginBottom:24 }}>{warranties.length} garantías asignadas a ti</p>

            {warranties.length === 0 ? (
              <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, padding:30, textAlign:'center', color:'#3D5A73' }}>
                <div style={{ fontSize:32, marginBottom:10 }}>🛡</div>
                <div>No tienes garantías asignadas.</div>
              </div>
            ) : (
              <div style={{ display:'flex', flexDirection:'column', gap:12 }}>
                {warranties.map(w => {
                  const st = WARRANTY_STATUS[w.status] || WARRANTY_STATUS.aprobada;
                  return (
                    <div key={w.id} style={{ background:'#0C1F38', border:'1px solid #16334F', borderLeft:`3px solid ${st.c}`, borderRadius:12, padding:'18px 20px' }}>
                      <div style={{ display:'flex', justifyContent:'space-between', alignItems:'flex-start', marginBottom:10 }}>
                        <div>
                          <div style={{ fontWeight:700, fontSize:15, color:'#E2E8F0', marginBottom:4 }}>🛡 {w.nombre_producto}</div>
                          <div style={{ fontSize:12, color:'#64748B', marginBottom:2 }}>👤 {w.nombre} · 📞 {w.telefono}</div>
                          <div style={{ fontSize:12, color:'#64748B', marginBottom:2 }}>Serial: <span style={{ color:'#94A3B8' }}>{w.numero_serial}</span></div>
                          <div style={{ fontSize:12, color:'#94A3B8' }}>Falla: {w.falla_reportada}</div>
                        </div>
                        <span style={{ background:st.c+'22', color:st.c, border:`1px solid ${st.c}44`, borderRadius:6, padding:'4px 10px', fontSize:11, fontWeight:700 }}>{st.l}</span>
                      </div>
                      <div style={{ display:'flex', gap:8, flexWrap:'wrap' }}>
                        <button onClick={() => setWarrantyDetail(w)}
                          style={{ background:'#0EA5E922', border:'1.5px solid #0EA5E9', borderRadius:7, padding:'7px 14px', fontSize:12, color:'#0EA5E9', cursor:'pointer', fontWeight:700 }}>
                          🔍 Ver detalle
                        </button>
                        {w.status === 'aprobada' && (
                          <button onClick={() => handleUpdateWarrantyStatus(w.id, 'en_proceso')}
                            style={{ background:'#06B6D422', border:'1.5px solid #06B6D4', borderRadius:7, padding:'7px 14px', fontSize:12, color:'#06B6D4', cursor:'pointer', fontWeight:700 }}>
                            ▶ Iniciar proceso
                          </button>
                        )}
                        {w.status === 'en_proceso' && (
                          <button onClick={() => setWarrantyClose(w)}
                            style={{ background:'#10B98122', border:'1.5px solid #10B981', borderRadius:7, padding:'7px 14px', fontSize:12, color:'#10B981', cursor:'pointer', fontWeight:700 }}>
                            ✓ Registrar intervención y cerrar
                          </button>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>
        )}

        {/* ── HISTORIAL UAS ── */}
        {tab === 'history' && (
          <div>
            <h1 style={{ fontSize:24, fontWeight:900, color:'#E2E8F0', marginBottom:4 }}>Historial UAS</h1>
            <p style={{ fontSize:13, color:'#3D5A73', marginBottom:24 }}>Historia clínica por dron</p>
            <div style={{ display:'flex', flexDirection:'column', gap:12 }}>
              {drones.map(d => (
                <div key={d.id} style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, padding:'18px 20px', display:'flex', justifyContent:'space-between', alignItems:'center' }}>
                  <div>
                    <div style={{ fontWeight:700, fontSize:14, color:'#E2E8F0', marginBottom:3 }}>{d.plate || d.numero_serie}</div>
                    <div style={{ fontSize:12, color:'#94A3B8' }}>🚁 {d.modelo || d.model}</div>
                  </div>
                  <button onClick={() => handleVerHistorial(d)}
                    style={{ background:'#0EA5E922', border:'1.5px solid #0EA5E9', borderRadius:7, padding:'8px 14px', fontSize:12, color:'#0EA5E9', cursor:'pointer', fontWeight:700 }}>
                    📋 Ver historial
                  </button>
                </div>
              ))}
              {drones.length === 0 && (
                <div style={{ color:'#3D5A73', fontSize:13, textAlign:'center', padding:20 }}>No hay drones registrados.</div>
              )}
            </div>
          </div>
        )}

        {/* ── Modal Ver Detalle Garantía ── */}
        {warrantyDetail && (
          <div style={{ position:'fixed', inset:0, background:'rgba(0,0,0,0.85)', display:'flex', alignItems:'center', justifyContent:'center', zIndex:1000, padding:20 }}>
            <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, width:'100%', maxWidth:640, maxHeight:'90vh', overflowY:'auto', padding:32, color:'#fff', fontFamily:"'Courier New',monospace" }}>
              <div style={{ display:'flex', justifyContent:'space-between', alignItems:'center', marginBottom:20 }}>
                <div style={{ fontSize:18, fontWeight:700, color:'#06B6D4' }}>🔍 Detalle de Garantía</div>
                <button onClick={() => setWarrantyDetail(null)} style={{ background:'transparent', border:'none', color:'#64748B', fontSize:20, cursor:'pointer' }}>✕</button>
              </div>
              {[
                ['Solicitante',     warrantyDetail.nombre],
                ['Cédula / NIT',    warrantyDetail.cedula_nit],
                ['Ciudad',          warrantyDetail.ciudad],
                ['Teléfono',        warrantyDetail.telefono],
                ['Producto',        warrantyDetail.nombre_producto],
                ['Serial',          warrantyDetail.numero_serial],
                ['Factura',         warrantyDetail.numero_factura],
                ['Falla reportada', warrantyDetail.falla_reportada],
                ['Contenido',       warrantyDetail.contenido],
                ['Observaciones',   warrantyDetail.observaciones || '—'],
              ].map(([k, v]) => (
                <div key={k} style={{ display:'flex', justifyContent:'space-between', padding:'7px 12px', background:'#050E1A', borderRadius:6, fontSize:12, marginBottom:4 }}>
                  <span style={{ color:'#64748b', minWidth:130 }}>{k}</span>
                  <span style={{ color:'#e2e8f0', fontWeight:600, textAlign:'right', maxWidth:'65%' }}>{v}</span>
                </div>
              ))}
              {warrantyDetail.adjuntos && warrantyDetail.adjuntos.length > 0 && (
                <div style={{ marginTop:16 }}>
                  <div style={{ fontSize:12, fontWeight:700, color:'#06B6D4', borderBottom:'1px solid #16334F', paddingBottom:8, marginBottom:12 }}>
                    📎 ARCHIVOS ({warrantyDetail.adjuntos.length})
                  </div>
                  {warrantyDetail.adjuntos.map((a, i) => (
                    <div key={i} style={{ background:'#050E1A', border:'1px solid #16334F', borderRadius:6, padding:'10px 14px', display:'flex', justifyContent:'space-between', alignItems:'center', marginBottom:6 }}>
                      <div style={{ fontSize:12, color:'#E2E8F0' }}>📎 {a.nombre}</div>
                      <a href={`${process.env.REACT_APP_API_URL || 'http://localhost:8000'}${a.url}`} target="_blank" rel="noreferrer"
                        style={{ background:'#06B6D422', border:'1px solid #06B6D444', borderRadius:6, padding:'5px 12px', fontSize:11, color:'#06B6D4', textDecoration:'none', fontWeight:700 }}>
                        Ver
                      </a>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>
        )}

        {/* ── Modal Registrar Intervención Garantía ── */}
        {warrantyClose && (
          <div style={{ position:'fixed', inset:0, background:'rgba(0,0,0,0.85)', display:'flex', alignItems:'center', justifyContent:'center', zIndex:1000, padding:20 }}>
            <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, width:'100%', maxWidth:580, maxHeight:'90vh', overflowY:'auto', padding:32, color:'#fff', fontFamily:"'Courier New',monospace" }}>
              <div style={{ fontSize:18, fontWeight:700, color:'#10B981', marginBottom:6 }}>✓ Registrar Intervención — Garantía</div>
              <div style={{ fontSize:12, color:'#64748b', marginBottom:20 }}>{warrantyClose.nombre_producto} · Serial: {warrantyClose.numero_serial}</div>
              <ModalIntervencion inter={intervencion} setInter={setIntervencion} inp={inp} resultados={RESULTADOS} />
              <div style={{ display:'flex', justifyContent:'space-between', marginTop:20 }}>
                <button onClick={() => { setWarrantyClose(null); setIntervencion({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' }); }}
                  style={{ background:'transparent', border:'1px solid #16334F', borderRadius:6, padding:'10px 24px', color:'#64748B', cursor:'pointer' }}>
                  Cancelar
                </button>
                <button onClick={handleCerrarGarantia} disabled={!intervencion.descripcion || !intervencion.resultado}
                  style={{ background:'#10B981', border:'none', borderRadius:6, padding:'10px 24px', color:'#fff', fontWeight:700, cursor:'pointer', opacity:(!intervencion.descripcion||!intervencion.resultado)?0.5:1 }}>
                  ✓ Guardar y cerrar garantía
                </button>
              </div>
            </div>
          </div>
        )}

        {/* ── Modal Registrar Intervención Cita ── */}
        {apptClose && (
          <div style={{ position:'fixed', inset:0, background:'rgba(0,0,0,0.85)', display:'flex', alignItems:'center', justifyContent:'center', zIndex:1000, padding:20 }}>
            <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, width:'100%', maxWidth:580, maxHeight:'90vh', overflowY:'auto', padding:32, color:'#fff', fontFamily:"'Courier New',monospace" }}>
              <div style={{ fontSize:18, fontWeight:700, color:'#10B981', marginBottom:6 }}>✓ Registrar Intervención — Cita</div>
              <div style={{ fontSize:12, color:'#64748b', marginBottom:20 }}>{apptClose.tipo || 'Cita'} · {apptClose.user?.name || '—'}</div>
              <ModalIntervencion inter={apptInter} setInter={setApptInter} inp={inp} resultados={RESULTADOS} />
              <div style={{ display:'flex', justifyContent:'space-between', marginTop:20 }}>
                <button onClick={() => { setApptClose(null); setApptInter({ descripcion:'', partes:'', seriales_nuevos:'', resultado:'', horas_trabajo:'' }); }}
                  style={{ background:'transparent', border:'1px solid #16334F', borderRadius:6, padding:'10px 24px', color:'#64748B', cursor:'pointer' }}>
                  Cancelar
                </button>
                <button onClick={handleCerrarCita} disabled={!apptInter.descripcion || !apptInter.resultado}
                  style={{ background:'#10B981', border:'none', borderRadius:6, padding:'10px 24px', color:'#fff', fontWeight:700, cursor:'pointer', opacity:(!apptInter.descripcion||!apptInter.resultado)?0.5:1 }}>
                  ✓ Guardar y cerrar cita
                </button>
              </div>
            </div>
          </div>
        )}

        {/* ── Modal Historial Dron ── */}
        {droneSelected && (
          <div style={{ position:'fixed', inset:0, background:'rgba(0,0,0,0.85)', display:'flex', alignItems:'center', justifyContent:'center', zIndex:1000, padding:20 }}>
            <div style={{ background:'#0C1F38', border:'1px solid #16334F', borderRadius:12, width:'100%', maxWidth:680, maxHeight:'90vh', overflowY:'auto', padding:32, color:'#fff', fontFamily:"'Courier New',monospace" }}>
              <div style={{ display:'flex', justifyContent:'space-between', alignItems:'center', marginBottom:20 }}>
                <div>
                  <div style={{ fontSize:18, fontWeight:700, color:'#0EA5E9' }}>📋 Historia Clínica</div>
                  <div style={{ fontSize:12, color:'#64748b' }}>{droneSelected.plate || droneSelected.numero_serie} · {droneSelected.modelo || droneSelected.model}</div>
                </div>
                <button onClick={() => setDroneSelected(null)} style={{ background:'transparent', border:'none', color:'#64748B', fontSize:20, cursor:'pointer' }}>✕</button>
              </div>
              {loadingHist ? (
                <div style={{ color:'#64748B', fontSize:13, textAlign:'center', padding:20 }}>Cargando historial...</div>
              ) : droneHistDetail.length === 0 ? (
                <div style={{ color:'#3D5A73', fontSize:13, textAlign:'center', padding:20 }}>Sin intervenciones registradas.</div>
              ) : (
                droneHistDetail.map((h, i) => (
                  <div key={h.id} style={{ background:'#050E1A', border:'1px solid #16334F', borderRadius:10, padding:'14px 16px', marginBottom:10 }}>
                    <div style={{ display:'flex', justifyContent:'space-between', marginBottom:8 }}>
                      <span style={{ fontWeight:700, color:'#0EA5E9', fontSize:13 }}>#{i+1} · {h.tipo}</span>
                      <span style={{ fontSize:11, color:'#64748b' }}>{new Date(h.created_at).toLocaleDateString('es-CO')}</span>
                    </div>
                    {[
                      ['Empresa',    h.empresa_nombre],
                      ['Técnico',    h.tecnico_nombre],
                      ['Descripción',h.descripcion],
                      ['Partes',     h.partes_reemplazadas || '—'],
                      ['Seriales',   h.seriales_nuevos || '—'],
                      ['Resultado',  h.resultado],
                      ['Horas',      h.horas_trabajo ? `${h.horas_trabajo}h` : '—'],
                    ].map(([k, v]) => (
                      <div key={k} style={{ display:'flex', gap:12, fontSize:12, marginBottom:4 }}>
                        <span style={{ color:'#64748b', minWidth:90 }}>{k}</span>
                        <span style={{ color:'#e2e8f0' }}>{v}</span>
                      </div>
                    ))}
                  </div>
                ))
              )}
            </div>
          </div>
        )}

      </main>
    </div>
  );
}

function ModalIntervencion({ inter, setInter, inp, resultados }) {
  const set = (k, v) => setInter(f => ({ ...f, [k]: v }));
  return (
    <div>
      <div style={{ marginBottom:14 }}>
        <label style={{ fontSize:12, color:'#94a3b8', display:'block', marginBottom:4 }}>Descripción del trabajo realizado *</label>
        <textarea value={inter.descripcion} onChange={e => set('descripcion', e.target.value)} rows={4}
          placeholder="Describe detalladamente el trabajo realizado..."
          style={{ ...inp, resize:'vertical' }} />
      </div>
      <div style={{ marginBottom:14 }}>
        <label style={{ fontSize:12, color:'#94a3b8', display:'block', marginBottom:4 }}>Partes / componentes reemplazados</label>
        <textarea value={inter.partes} onChange={e => set('partes', e.target.value)} rows={3}
          placeholder="Ej: Motor frontal derecho (serial: XYZ123)..."
          style={{ ...inp, resize:'vertical' }} />
      </div>
      <div style={{ marginBottom:14 }}>
        <label style={{ fontSize:12, color:'#94a3b8', display:'block', marginBottom:4 }}>Seriales nuevos instalados</label>
        <input value={inter.seriales_nuevos} onChange={e => set('seriales_nuevos', e.target.value)}
          placeholder="Separar por comas" style={inp} />
      </div>
      <div style={{ marginBottom:14 }}>
        <label style={{ fontSize:12, color:'#94a3b8', display:'block', marginBottom:4 }}>Horas de trabajo</label>
        <input type="number" value={inter.horas_trabajo} onChange={e => set('horas_trabajo', e.target.value)}
          placeholder="Ej: 3" style={inp} />
      </div>
      <div style={{ marginBottom:8 }}>
        <label style={{ fontSize:12, color:'#94a3b8', display:'block', marginBottom:8 }}>Resultado final *</label>
        <div style={{ display:'flex', gap:8, flexWrap:'wrap' }}>
          {resultados.map(r => (
            <button key={r} onClick={() => set('resultado', r)}
              style={{ background:inter.resultado===r?'#10B98122':'#050E1A', border:`1px solid ${inter.resultado===r?'#10B981':'#16334F'}`, borderRadius:6, padding:'6px 12px', fontSize:11, color:inter.resultado===r?'#10B981':'#64748b', cursor:'pointer', fontWeight:600 }}>
              {r}
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}