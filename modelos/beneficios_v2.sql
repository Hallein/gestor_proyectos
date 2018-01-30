/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     30-01-2018 10:58:04                          */
/*==============================================================*/


drop table if exists ALUMINIO;

drop table if exists CRISTAL;

drop table if exists DETALLE_VENTA;

drop table if exists PRODUCTO;

drop table if exists USUARIO;

drop table if exists VENTA;

/*==============================================================*/
/* Table: ALUMINIO                                              */
/*==============================================================*/
create table ALUMINIO
(
   PID                  int not null,
   ATIPO_LINEA          varchar(40),
   ACOLOR               varchar(30),
   ATIPO_VIDRIO         varchar(40),
   AVALOR_MINIMO        int,
   ADIBUJO              varchar(50),
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
   INSTALACION          int,
   DETALLE              varchar(255)
);

/*==============================================================*/
/* Table: PRODUCTO                                              */
/*==============================================================*/
create table PRODUCTO
(
   PID                  int not null auto_increment,
   PNOMBRE              varchar(100),
   PDESCRIPCION         varchar(255),
   PPRECIOM2            int,
   PESPESOR             int,
   PTIPO                smallint,
   primary key (PID)
);

/*==============================================================*/
/* Table: USUARIO                                               */
/*==============================================================*/
create table USUARIO
(
   UID                  int not null auto_increment,
   USERNAME             varchar(60),
   PASSWORD             varchar(60),
   TOKEN                varchar(255),
   primary key (UID)
);

/*==============================================================*/
/* Table: VENTA                                                 */
/*==============================================================*/
create table VENTA
(
   VID                  int not null auto_increment,
   UID                  int,
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

alter table VENTA add constraint FK_RELATIONSHIP_3 foreign key (UID)
      references USUARIO (UID) on delete restrict on update restrict;

