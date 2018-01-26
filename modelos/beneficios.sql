/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     26-01-2018 10:56:37                          */
/*==============================================================*/


drop table if exists ALUMINIO;

drop table if exists CRISTAL;

drop table if exists DETALLE_VENTA;

drop table if exists PRODUCTO;

drop table if exists VENTA;

/*==============================================================*/
/* Table: ALUMINIO                                              */
/*==============================================================*/
create table ALUMINIO
(
   PID                  int not null,
   primary key (PID)
);

/*==============================================================*/
/* Table: CRISTAL                                               */
/*==============================================================*/
create table CRISTAL
(
   PID                  int not null,
   CDIBUJO              varchar(50),
   CTIPO                varchar(100),
   primary key (PID)
);

/*==============================================================*/
/* Table: DETALLE_VENTA                                         */
/*==============================================================*/
create table DETALLE_VENTA
(
   VID                  int,
   PID                  int,
   CANTIDAD             int,
   SUBTOTAL             int,
   PULIDO               bool,
   CORTE_ESPECIAL       int,
   INSTALACION          int
);

/*==============================================================*/
/* Table: PRODUCTO                                              */
/*==============================================================*/
create table PRODUCTO
(
   PID                  int not null auto_increment,
   PNOMBRE              varchar(100),
   PDESCRIPCION         varchar(255),
   PPRECIOM2            numeric(8,0),
   PESPESOR             int,
   primary key (PID)
);

/*==============================================================*/
/* Table: VENTA                                                 */
/*==============================================================*/
create table VENTA
(
   VID                  int not null auto_increment,
   VTOTAL               int,
   VFECHA               datetime,
   primary key (VID)
);

alter table ALUMINIO add constraint FK_INHERITANCE_3 foreign key (PID)
      references PRODUCTO (PID) on delete restrict on update restrict;

alter table CRISTAL add constraint FK_INHERITANCE_4 foreign key (PID)
      references PRODUCTO (PID) on delete restrict on update restrict;

alter table DETALLE_VENTA add constraint FK_RELATIONSHIP_1 foreign key (PID)
      references PRODUCTO (PID) on delete restrict on update restrict;

alter table DETALLE_VENTA add constraint FK_RELATIONSHIP_2 foreign key (VID)
      references VENTA (VID) on delete restrict on update restrict;

