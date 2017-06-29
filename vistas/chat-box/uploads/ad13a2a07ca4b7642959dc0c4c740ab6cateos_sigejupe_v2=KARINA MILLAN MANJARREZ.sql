#200	202
#REGISTRO SOLICITUD
select * from tblsolicitudescateos order by idSolicitudCateo desc;
select * from tblcateos order by idCateo desc;
select * from tblobjetos order by idSolicitudCateo desc;
select * from tblpersonas order by idSolicitudCateo desc;
select * from tblimputadoscateos order by idSolicitudCateo desc;
select * from tblofendidoscateos order by idSolicitudCateo desc;
select * from tbldelitoscateos order by idSolicitudCateo desc;
select * from tblcontadores ;
select * from tbljuzgadorescateos order by idSolicitudCateo desc;
select * from tblministeriosresponsables order by idSolicitudCateo desc;
select * from tblbitacoranotificaciones order by idBitacoraNotificacion desc; #llamadas / correos
SELECT * FROM tblbitacoramovimientos order by idBitacoraMovimiento desc; 

#RESPUESTA DE CATEO
select * from tblcateos order by idCateo desc;
select * from tblobjetos order by idSolicitudCateo desc;
select * from tblpersonas order by idSolicitudCateo desc;
select * from tblbitacoranotificaciones order by idBitacoraNotificacion desc; #llamadas / correos
SELECT * FROM tblbitacoramovimientos order by idBitacoraMovimiento desc; 



select * from tbltelefonosjuzgadores;
select * from tbltiposlada;
select * from tblestatussolicitudescateos;
select * from tblrespuestasolicitudcateo;
select * from tblcarpetasjudiciales;
select * from tblmediosnotificaciones;
select * from tblestatusnotificaciones;
select * from tbljuzgados;
select * from tbljuzgadores;
select * from tbltiposjuzgadores;
select * from tbltiposactuaciones;
select * from tbldelitos;
select * from tbljuzgadores;
select * from tblusuarios;
select * from tblacciones ;
select * from tblterminos;
select * from tbljuzgadoresjuzgados;




SELECT idCarpetaJudicial,cveEtapaProcesal,cveConsignacion,cveTipoCarpeta,numero,anio,fechaRadicacion
,fechaRegistro,fechaActualizacion,activo,cveJuzgado,carpetaInv,nuc,cveUsuario,observaciones,numImputados
,numOfendidos,numDelitos,cveEstatusCarpeta,incompetencia,cveTipoIncompetencia,acumulado,numAcumulado
,fechaTermino,cveConclucion,ponderacion FROM tblcarpetasjudiciales  WHERE cveTipoCarpeta='2' AND numero
='2' AND anio='2' AND cveJuzgado='762';

SELECT idCarpetaJudicial,cveEtapaProcesal,cveConsignacion,cveTipoCarpeta,numero,anio,fechaRadicacion
,fechaRegistro,fechaActualizacion,activo,cveJuzgado,carpetaInv,nuc,cveUsuario,observaciones,numImputados
,numOfendidos,numDelitos,cveEstatusCarpeta,incompetencia,cveTipoIncompetencia,acumulado,numAcumulado
,fechaTermino,cveConclucion,ponderacion FROM tblcarpetasjudiciales  WHERE cveTipoCarpeta='1' AND numero
='1' AND anio='2015' AND cveJuzgado='762';

Select date_format(now(),'%Y%m%d') as Fecha;

SELECT idJuzgador,cveUsuario,numEmpleado,titulo,paterno,materno,nombre,cveTipoJuzgador,sorteo,programable,activo,fechaRegistro,fechaActualizacion FROM tbljuzgadores WHERE idJuzgador='285' AND activo='S';
INSERT INTO tbljuzgadorescateos(idSolicitudCateo,idJuzgador,activo,fechaRegistro,fechaActualizacion) VALUES('103','276','S',now(),now());
INSERT INTO tbldelitoscateos(idSolicitudCateo,cveDelito,activo,fechaRegistro,fechaActualizacion) VALUES('52','150','S',now(),now());
INSERT INTO tblbitacoramovimientos(cveAccion,observaciones,cveUsuario,cvePerfil,cveAdscripcion,fechaRegistro) VALUES('36','AGREGO SOLICITUD DE CATEO NUMERO: 69 AÑO: 2015 JUEZ285 ENVIO CORREO ELECTRONICO A EL JUEZ Y EL ADMINISTRADOR DEL JUZGADO','2','160','69',now());
SELECT idPersona,idSolicitudCateo,paterno,materno,nombre,domicilio,cveMunicipio,cveGenero,cveOrigen FROM tblpersonas WHERE idSolicitudCateo='168' AND cveOrigen='1';
select * from tbljuzgados;

select * from tblcausas;

select * from tblcarpetasjudiciales;

SELECT idCarpetaJudicial,cveEtapaProcesal,cveConsignacion,cveTipoCarpeta,numero,anio,fechaRadicacion,fechaRegistro,fechaActualizacion,activo,cveJuzgado,carpetaInv,nuc,cveUsuario,observaciones,numImputados,numOfendidos,numDelitos,cveEstatusCarpeta,incompetencia,cveTipoIncompetencia,acumulado,numAcumulado,fechaTermino,cveConclucion,ponderacion 
FROM tblcarpetasjudiciales 
WHERE numero='1' AND anio='2015' AND cveJuzgado='1';

select * from tblcarpetasjudiciales;

select * from tblimputadoscarpetas;
select * from tblofendidoscarpetas;
select * from tbldelitoscarpetas;


select * from tblcataudiencias where desCatAudiencia like "%cate%";

select * from tbltiposaudiencias;


select * from tblestatussolicitudescateos;



select * from tbldelitos;



select cj.idCarpetaJudicial, oc.idOfendidoCarpeta, ic.idImputadoCarpeta, dc.idDelitoCarpeta, cj.*
from   tblcarpetasjudiciales as cj, tblofendidoscarpetas as oc, tblimputadoscarpetas as ic, tbldelitoscarpetas as dc
where  cj.idCarpetaJudicial = oc.idCarpetaJudicial
and    cj.idCarpetaJudicial = ic.idCarpetaJudicial
and    cj.idCarpetaJudicial = dc.idCarpetaJudicial
#and    cj.idSolicitudAudiencia in (179,214,240)
and    cj.activo = "S"
#and    oc.activo = "S"
#and    ic.activo = "S"
#and    dc.activo = "S";
;

select * from tblcarpetasjudiciales;
SELECT * FROM tbltiposcarpetas;
SELECT idBitacoraNotificacion,cveMedioNotificacion,cveTipoCarpeta,cveTipoActuacion,cveEstatusNotificacion,idReferencia,numero,anio,cvejuzgado,cveUsuario,medio,movimiento,fechaRegistro,fechaActualizacion FROM tblbitacoranotificaciones ;

select * from tblbitacoranotificaciones ;
SELECT idBitacoraNotificacion,cveMedioNotificacion,cveTipoCarpeta,cveTipoActuacion,cveEstatusNotificacion,idReferencia,numero,anio,cvejuzgado,cveUsuario,medio,movimiento,fechaRegistro,fechaActualizacion, count(idBitacoraNotificacion) as totalRegistros FROM tblbitacoranotificaciones WHERE cveMedioNotificacion='1' AND cveTipoActuacion='12' AND idReferencia='237' group by cveUsuario;

#update tblbitacoranotificaciones set cveUsuario = 46 where cveUsuario = "0"  and idBitacoraNotificacion > 0;

#truncate table tblbitacoranotificaciones;

select * from tbldelitos order by desDelito asc;


ALTER TABLE `htsj_sigejupe`.`tbljuzgadoresordenes` 
ADD CONSTRAINT `tbljuzgadoresordenes_idJuzgador_fk`
  FOREIGN KEY (`idJuzgador`)
  REFERENCES `htsj_sigejupe`.`tbljuzgadores` (`idJuzgador`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `tbljuzgadoresordenes_idSolicitudOrdenes_fk`
  FOREIGN KEY (`idSolicitudOrden`)
  REFERENCES `htsj_sigejupe`.`tblsolicitudesordenes` (`idSolicitudOrden`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;