--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

-- Started on 2025-07-10 14:15:57

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 925 (class 1247 OID 41088)
-- Name: estado_conteo_enum; Type: TYPE; Schema: public; Owner: developer
--

CREATE TYPE public.estado_conteo_enum AS ENUM (
    'Pendiente',
    'Completado',
    'Cancelado'
);


ALTER TYPE public.estado_conteo_enum OWNER TO developer;

--
-- TOC entry 254 (class 1255 OID 24593)
-- Name: actualizar_timestamp(); Type: FUNCTION; Schema: public; Owner: developer
--

CREATE FUNCTION public.actualizar_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.actualizado_en = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_timestamp() OWNER TO developer;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 251 (class 1259 OID 65680)
-- Name: carrito; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.carrito (
    id integer NOT NULL,
    usuario_id integer,
    session_id character varying(64),
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.carrito OWNER TO developer;

--
-- TOC entry 250 (class 1259 OID 65679)
-- Name: carrito_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.carrito_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carrito_id_seq OWNER TO developer;

--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 250
-- Name: carrito_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.carrito_id_seq OWNED BY public.carrito.id;


--
-- TOC entry 253 (class 1259 OID 65694)
-- Name: carrito_items; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.carrito_items (
    id integer NOT NULL,
    carrito_id integer,
    producto_id integer,
    talla character varying(16),
    cantidad integer NOT NULL,
    precio_unitario numeric(10,2),
    estado character varying(16) DEFAULT 'activo'::character varying,
    CONSTRAINT carrito_items_cantidad_check CHECK ((cantidad > 0))
);


ALTER TABLE public.carrito_items OWNER TO developer;

--
-- TOC entry 252 (class 1259 OID 65693)
-- Name: carrito_items_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.carrito_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carrito_items_id_seq OWNER TO developer;

--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 252
-- Name: carrito_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.carrito_items_id_seq OWNED BY public.carrito_items.id;


--
-- TOC entry 247 (class 1259 OID 49306)
-- Name: catalogo_productos; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.catalogo_productos (
    id integer NOT NULL,
    producto_id integer NOT NULL,
    sucursal_id integer NOT NULL,
    ingreso_id integer NOT NULL,
    imagen_id integer NOT NULL,
    estado boolean DEFAULT true,
    estado_oferta boolean DEFAULT false,
    limite_oferta date,
    oferta numeric(5,2)
);


ALTER TABLE public.catalogo_productos OWNER TO developer;

--
-- TOC entry 220 (class 1259 OID 24580)
-- Name: categoria; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.categoria (
    id_categoria integer NOT NULL,
    nombre_categoria character varying(100) NOT NULL,
    descripcion_categoria text,
    estado_categoria boolean DEFAULT true,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.categoria OWNER TO developer;

--
-- TOC entry 219 (class 1259 OID 24579)
-- Name: categoria_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.categoria_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categoria_id_categoria_seq OWNER TO developer;

--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 219
-- Name: categoria_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.categoria_id_categoria_seq OWNED BY public.categoria.id_categoria;


--
-- TOC entry 245 (class 1259 OID 41122)
-- Name: conteos_ciclicos; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.conteos_ciclicos (
    id_conteo integer NOT NULL,
    producto_id integer NOT NULL,
    sucursal_id integer NOT NULL,
    cantidad_real integer NOT NULL,
    cantidad_sistema integer NOT NULL,
    diferencia integer,
    fecha_conteo date NOT NULL,
    usuario_id integer NOT NULL,
    comentarios text,
    estado_conteo public.estado_conteo_enum,
    fecha_ajuste date
);


ALTER TABLE public.conteos_ciclicos OWNER TO developer;

--
-- TOC entry 244 (class 1259 OID 41121)
-- Name: conteos_ciclicos_id_conteo_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.conteos_ciclicos_id_conteo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.conteos_ciclicos_id_conteo_seq OWNER TO developer;

--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 244
-- Name: conteos_ciclicos_id_conteo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.conteos_ciclicos_id_conteo_seq OWNED BY public.conteos_ciclicos.id_conteo;


--
-- TOC entry 243 (class 1259 OID 41069)
-- Name: detalles_envio; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.detalles_envio (
    id integer NOT NULL,
    envio_id integer,
    producto_id integer,
    cantidad integer
);


ALTER TABLE public.detalles_envio OWNER TO developer;

--
-- TOC entry 242 (class 1259 OID 41068)
-- Name: detalles_envio_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.detalles_envio_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detalles_envio_id_seq OWNER TO developer;

--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 242
-- Name: detalles_envio_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.detalles_envio_id_seq OWNED BY public.detalles_envio.id;


--
-- TOC entry 241 (class 1259 OID 41044)
-- Name: envios; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.envios (
    id integer NOT NULL,
    venta_id integer,
    direccion_envio character varying(255),
    estado_envio character varying(50),
    fecha_envio timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_estimada_entrega timestamp without time zone,
    metodo_envio character varying(50),
    costo_envio numeric(10,2),
    rastreo_codigo character varying(100)
);


ALTER TABLE public.envios OWNER TO developer;

--
-- TOC entry 240 (class 1259 OID 41043)
-- Name: envios_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.envios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.envios_id_seq OWNER TO developer;

--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 240
-- Name: envios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.envios_id_seq OWNED BY public.envios.id;


--
-- TOC entry 235 (class 1259 OID 32783)
-- Name: imagenes_producto; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.imagenes_producto (
    id integer NOT NULL,
    producto_id integer,
    url_imagen character varying NOT NULL,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    vista_producto integer
);


ALTER TABLE public.imagenes_producto OWNER TO developer;

--
-- TOC entry 234 (class 1259 OID 32782)
-- Name: imagenes_producto_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.imagenes_producto_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.imagenes_producto_id_seq OWNER TO developer;

--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 234
-- Name: imagenes_producto_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.imagenes_producto_id_seq OWNED BY public.imagenes_producto.id;


--
-- TOC entry 248 (class 1259 OID 57483)
-- Name: ingreso_ref_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.ingreso_ref_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ingreso_ref_seq OWNER TO developer;

--
-- TOC entry 231 (class 1259 OID 24766)
-- Name: ingreso; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.ingreso (
    id integer NOT NULL,
    id_producto integer NOT NULL,
    ref integer DEFAULT nextval('public.ingreso_ref_seq'::regclass) NOT NULL,
    precio_costo numeric(10,2) NOT NULL,
    precio_costo_igv numeric(10,2) NOT NULL,
    precio_venta numeric(10,2) NOT NULL,
    utilidad_esperada numeric(10,2) NOT NULL,
    utilidad_neta numeric(10,2),
    cantidad integer DEFAULT 1,
    fecha_ingreso timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_usuario integer NOT NULL,
    id_sucursal integer
);


ALTER TABLE public.ingreso OWNER TO developer;

--
-- TOC entry 230 (class 1259 OID 24765)
-- Name: ingreso_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.ingreso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ingreso_id_seq OWNER TO developer;

--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 230
-- Name: ingreso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.ingreso_id_seq OWNED BY public.ingreso.id;


--
-- TOC entry 229 (class 1259 OID 24733)
-- Name: inventario_sucursal; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.inventario_sucursal (
    id_producto integer NOT NULL,
    id_sucursal integer NOT NULL,
    cantidad integer DEFAULT 0,
    fecha_actualizacion date,
    estado character varying,
    ref integer NOT NULL
);


ALTER TABLE public.inventario_sucursal OWNER TO developer;

--
-- TOC entry 249 (class 1259 OID 57494)
-- Name: inventario_sucursal_ref_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.inventario_sucursal_ref_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventario_sucursal_ref_seq OWNER TO developer;

--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 249
-- Name: inventario_sucursal_ref_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.inventario_sucursal_ref_seq OWNED BY public.inventario_sucursal.ref;


--
-- TOC entry 239 (class 1259 OID 41025)
-- Name: items_venta; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.items_venta (
    id integer NOT NULL,
    venta_id integer,
    producto_id integer,
    cantidad integer,
    precio_unitario numeric(10,2)
);


ALTER TABLE public.items_venta OWNER TO developer;

--
-- TOC entry 238 (class 1259 OID 41024)
-- Name: items_venta_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.items_venta_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.items_venta_id_seq OWNER TO developer;

--
-- TOC entry 5162 (class 0 OID 0)
-- Dependencies: 238
-- Name: items_venta_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.items_venta_id_seq OWNED BY public.items_venta.id;


--
-- TOC entry 228 (class 1259 OID 24694)
-- Name: kardex; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.kardex (
    id_kardex integer NOT NULL,
    id_producto integer NOT NULL,
    cantidad integer NOT NULL,
    tipo_movimiento character varying(50) NOT NULL,
    precio_costo numeric(10,2) NOT NULL,
    fecha_movimiento timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_usuario integer NOT NULL
);


ALTER TABLE public.kardex OWNER TO developer;

--
-- TOC entry 227 (class 1259 OID 24693)
-- Name: kardex_id_kardex_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.kardex_id_kardex_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.kardex_id_kardex_seq OWNER TO developer;

--
-- TOC entry 5163 (class 0 OID 0)
-- Dependencies: 227
-- Name: kardex_id_kardex_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.kardex_id_kardex_seq OWNED BY public.kardex.id_kardex;


--
-- TOC entry 226 (class 1259 OID 24661)
-- Name: producto; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.producto (
    id_producto integer NOT NULL,
    ref_producto character varying NOT NULL,
    nombre_producto character varying NOT NULL,
    descripcion_producto text,
    id_subcategoria integer,
    talla_producto character varying,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    estado boolean
);


ALTER TABLE public.producto OWNER TO developer;

--
-- TOC entry 225 (class 1259 OID 24660)
-- Name: producto_id_producto_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.producto_id_producto_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.producto_id_producto_seq OWNER TO developer;

--
-- TOC entry 5164 (class 0 OID 0)
-- Dependencies: 225
-- Name: producto_id_producto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.producto_id_producto_seq OWNED BY public.producto.id_producto;


--
-- TOC entry 246 (class 1259 OID 49305)
-- Name: producto_vista_cliente_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.producto_vista_cliente_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.producto_vista_cliente_id_seq OWNER TO developer;

--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 246
-- Name: producto_vista_cliente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.producto_vista_cliente_id_seq OWNED BY public.catalogo_productos.id;


--
-- TOC entry 224 (class 1259 OID 24632)
-- Name: subcategoria; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.subcategoria (
    id_subcategoria integer NOT NULL,
    nombre_subcategoria character varying NOT NULL,
    descripcion_subcategoria text,
    id_categoria integer,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    estado boolean
);


ALTER TABLE public.subcategoria OWNER TO developer;

--
-- TOC entry 223 (class 1259 OID 24631)
-- Name: subcategoria_id_subcategoria_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.subcategoria_id_subcategoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.subcategoria_id_subcategoria_seq OWNER TO developer;

--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 223
-- Name: subcategoria_id_subcategoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.subcategoria_id_subcategoria_seq OWNED BY public.subcategoria.id_subcategoria;


--
-- TOC entry 222 (class 1259 OID 24596)
-- Name: sucursal; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.sucursal (
    id_sucursal integer NOT NULL,
    nombre_sucursal character varying NOT NULL,
    tipo_sucursal character varying NOT NULL,
    direccion_sucursal text,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    estado_sucursal boolean,
    id_supervisor integer
);


ALTER TABLE public.sucursal OWNER TO developer;

--
-- TOC entry 221 (class 1259 OID 24595)
-- Name: sucursales_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.sucursales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sucursales_id_seq OWNER TO developer;

--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 221
-- Name: sucursales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.sucursales_id_seq OWNED BY public.sucursal.id_sucursal;


--
-- TOC entry 233 (class 1259 OID 24788)
-- Name: transferencia; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.transferencia (
    id integer NOT NULL,
    id_producto integer NOT NULL,
    id_sucursal_origen integer NOT NULL,
    id_sucursal_destino integer NOT NULL,
    cantidad integer NOT NULL,
    fecha_transferencia timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_usuario integer NOT NULL
);


ALTER TABLE public.transferencia OWNER TO developer;

--
-- TOC entry 232 (class 1259 OID 24787)
-- Name: transferencia_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.transferencia_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transferencia_id_seq OWNER TO developer;

--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 232
-- Name: transferencia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.transferencia_id_seq OWNED BY public.transferencia.id;


--
-- TOC entry 218 (class 1259 OID 16401)
-- Name: usuario; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    ref_usuario character varying(255),
    nombre_usuario character varying(255),
    email_usuario character varying(255),
    pass_usuario text,
    telefono_usuario character varying(20),
    direccion_usuario text,
    rol_usuario character varying(50),
    estado_usuario boolean,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.usuario OWNER TO developer;

--
-- TOC entry 217 (class 1259 OID 16400)
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.usuario_id_usuario_seq OWNER TO developer;

--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 217
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- TOC entry 237 (class 1259 OID 41004)
-- Name: ventas; Type: TABLE; Schema: public; Owner: developer
--

CREATE TABLE public.ventas (
    id integer NOT NULL,
    ref character varying(255),
    usuario_id integer,
    sucursal_id integer,
    estado character varying(50),
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    metodo_pago character varying(50),
    total numeric(10,2),
    estado_pago character varying(50)
);


ALTER TABLE public.ventas OWNER TO developer;

--
-- TOC entry 236 (class 1259 OID 41003)
-- Name: ventas_id_seq; Type: SEQUENCE; Schema: public; Owner: developer
--

CREATE SEQUENCE public.ventas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ventas_id_seq OWNER TO developer;

--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 236
-- Name: ventas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: developer
--

ALTER SEQUENCE public.ventas_id_seq OWNED BY public.ventas.id;


--
-- TOC entry 4872 (class 2604 OID 65683)
-- Name: carrito id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito ALTER COLUMN id SET DEFAULT nextval('public.carrito_id_seq'::regclass);


--
-- TOC entry 4875 (class 2604 OID 65697)
-- Name: carrito_items id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito_items ALTER COLUMN id SET DEFAULT nextval('public.carrito_items_id_seq'::regclass);


--
-- TOC entry 4869 (class 2604 OID 49309)
-- Name: catalogo_productos id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.catalogo_productos ALTER COLUMN id SET DEFAULT nextval('public.producto_vista_cliente_id_seq'::regclass);


--
-- TOC entry 4835 (class 2604 OID 24583)
-- Name: categoria id_categoria; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.categoria_id_categoria_seq'::regclass);


--
-- TOC entry 4868 (class 2604 OID 41125)
-- Name: conteos_ciclicos id_conteo; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos ALTER COLUMN id_conteo SET DEFAULT nextval('public.conteos_ciclicos_id_conteo_seq'::regclass);


--
-- TOC entry 4867 (class 2604 OID 41072)
-- Name: detalles_envio id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.detalles_envio ALTER COLUMN id SET DEFAULT nextval('public.detalles_envio_id_seq'::regclass);


--
-- TOC entry 4865 (class 2604 OID 41047)
-- Name: envios id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.envios ALTER COLUMN id SET DEFAULT nextval('public.envios_id_seq'::regclass);


--
-- TOC entry 4858 (class 2604 OID 32786)
-- Name: imagenes_producto id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.imagenes_producto ALTER COLUMN id SET DEFAULT nextval('public.imagenes_producto_id_seq'::regclass);


--
-- TOC entry 4852 (class 2604 OID 24769)
-- Name: ingreso id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso ALTER COLUMN id SET DEFAULT nextval('public.ingreso_id_seq'::regclass);


--
-- TOC entry 4851 (class 2604 OID 57495)
-- Name: inventario_sucursal ref; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.inventario_sucursal ALTER COLUMN ref SET DEFAULT nextval('public.inventario_sucursal_ref_seq'::regclass);


--
-- TOC entry 4864 (class 2604 OID 41028)
-- Name: items_venta id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.items_venta ALTER COLUMN id SET DEFAULT nextval('public.items_venta_id_seq'::regclass);


--
-- TOC entry 4848 (class 2604 OID 24697)
-- Name: kardex id_kardex; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.kardex ALTER COLUMN id_kardex SET DEFAULT nextval('public.kardex_id_kardex_seq'::regclass);


--
-- TOC entry 4845 (class 2604 OID 24664)
-- Name: producto id_producto; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.producto ALTER COLUMN id_producto SET DEFAULT nextval('public.producto_id_producto_seq'::regclass);


--
-- TOC entry 4842 (class 2604 OID 24635)
-- Name: subcategoria id_subcategoria; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.subcategoria ALTER COLUMN id_subcategoria SET DEFAULT nextval('public.subcategoria_id_subcategoria_seq'::regclass);


--
-- TOC entry 4839 (class 2604 OID 24599)
-- Name: sucursal id_sucursal; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.sucursal ALTER COLUMN id_sucursal SET DEFAULT nextval('public.sucursales_id_seq'::regclass);


--
-- TOC entry 4856 (class 2604 OID 24791)
-- Name: transferencia id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia ALTER COLUMN id SET DEFAULT nextval('public.transferencia_id_seq'::regclass);


--
-- TOC entry 4832 (class 2604 OID 16404)
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- TOC entry 4861 (class 2604 OID 41007)
-- Name: ventas id; Type: DEFAULT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ventas ALTER COLUMN id SET DEFAULT nextval('public.ventas_id_seq'::regclass);


--
-- TOC entry 5145 (class 0 OID 65680)
-- Dependencies: 251
-- Data for Name: carrito; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.carrito (id, usuario_id, session_id, fecha_creacion, fecha_actualizacion) FROM stdin;
1	1	\N	2025-07-10 09:31:06.844603	2025-07-10 09:31:06.844603
\.


--
-- TOC entry 5147 (class 0 OID 65694)
-- Dependencies: 253
-- Data for Name: carrito_items; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.carrito_items (id, carrito_id, producto_id, talla, cantidad, precio_unitario, estado) FROM stdin;
1	1	7	M	3	410.64	eliminado
10	1	6	L	4	273.76	eliminado
2	1	6	L	1	273.76	eliminado
11	1	6	L	1	273.76	eliminado
12	1	6	L	2	273.76	eliminado
13	1	6	L	3	273.76	eliminado
14	1	7	M	1	410.64	activo
4	1	6	L	9	273.76	eliminado
5	1	6	L	1	273.76	eliminado
3	1	5	L	3	348.00	eliminado
6	1	7	M	1	410.64	eliminado
7	1	6	L	6	273.76	eliminado
8	1	5	L	3	348.00	eliminado
9	1	7	M	6	410.64	eliminado
\.


--
-- TOC entry 5141 (class 0 OID 49306)
-- Dependencies: 247
-- Data for Name: catalogo_productos; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.catalogo_productos (id, producto_id, sucursal_id, ingreso_id, imagen_id, estado, estado_oferta, limite_oferta, oferta) FROM stdin;
4	5	7	11	3	t	t	2025-07-31	10.00
5	6	7	12	4	t	t	2025-07-07	10.00
7	7	7	13	6	t	t	2025-07-31	10.00
10	4	7	14	7	t	f	\N	\N
\.


--
-- TOC entry 5114 (class 0 OID 24580)
-- Dependencies: 220
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.categoria (id_categoria, nombre_categoria, descripcion_categoria, estado_categoria, creado_en, actualizado_en) FROM stdin;
2	Pantalon	Pantalon	t	2025-06-29 05:23:28	2025-06-30 10:27:53.563346
3	Polos	Variedad de polos urbanos	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
6	Pantalones	Joggers y cargos modernos	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
7	Chaquetas	Casacas con estilo urbano	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
8	Conjuntos	Outfits combinados	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
9	Ropa Deportiva	Estilo activo y urbano	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
10	Ropa Unisex	Prendas para todos	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
11	Accesorios	Complementos streetwear	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
13	Gorras	Gorras y bucket hats	t	2025-06-30 10:39:57.021445	2025-06-30 10:39:57.021445
12	Calzado	Sneakers	t	2025-06-30 10:39:57.021445	2025-06-30 10:41:03.517621
14	Colecciones Exclusivas	Prendas edicion limitada	t	2025-06-30 10:39:57.021445	2025-06-30 10:41:11.979985
1	Polo	Polo	f	2025-06-21 16:19:24	2025-06-30 10:56:58.001504
15	l	l	f	2025-06-30 17:57:10	2025-06-30 10:57:18.530053
5	Sudaderas	Hoodies y buzos comodos	t	2025-06-30 10:39:57.021445	2025-06-30 10:57:27.767316
4	Camisetas	Camisetas con disenos Unicos	t	2025-06-30 10:39:57.021445	2025-06-30 10:57:44.235746
16	Makanaky	12	f	2025-07-01 04:53:14	2025-06-30 21:53:32.865721
\.


--
-- TOC entry 5139 (class 0 OID 41122)
-- Dependencies: 245
-- Data for Name: conteos_ciclicos; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.conteos_ciclicos (id_conteo, producto_id, sucursal_id, cantidad_real, cantidad_sistema, diferencia, fecha_conteo, usuario_id, comentarios, estado_conteo, fecha_ajuste) FROM stdin;
6	2	6	20	20	0	2025-06-03	1		Cancelado	2025-07-03
12	2	7	40	40	0	2025-07-03	1		Completado	\N
4	2	7	40	40	0	2025-07-03	1		Cancelado	2025-07-03
10	2	7	40	40	0	2025-07-03	1		Cancelado	2025-07-03
11	2	7	40	40	0	2025-07-03	1		Cancelado	2025-07-03
1	2	6	39	40	-1	2025-07-03	1	Conteo manual realizado por auditoria	Completado	2025-07-04
5	2	5	21	20	1	2025-06-03	1		Cancelado	2025-07-04
7	2	5	20	20	0	2025-07-03	1		Cancelado	2025-07-04
8	2	5	20	20	0	2025-07-03	1		Cancelado	2025-07-04
9	2	5	21	20	1	2025-07-03	1		Completado	2025-07-04
\.


--
-- TOC entry 5137 (class 0 OID 41069)
-- Dependencies: 243
-- Data for Name: detalles_envio; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.detalles_envio (id, envio_id, producto_id, cantidad) FROM stdin;
\.


--
-- TOC entry 5135 (class 0 OID 41044)
-- Dependencies: 241
-- Data for Name: envios; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.envios (id, venta_id, direccion_envio, estado_envio, fecha_envio, fecha_estimada_entrega, metodo_envio, costo_envio, rastreo_codigo) FROM stdin;
\.


--
-- TOC entry 5129 (class 0 OID 32783)
-- Dependencies: 235
-- Data for Name: imagenes_producto; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.imagenes_producto (id, producto_id, url_imagen, creado_en, actualizado_en, vista_producto) FROM stdin;
1	1	https://res.cloudinary.com/dylr6druv/image/upload/v1751038883/beige-f_g2mu9k.webp	2025-06-27 00:00:00	2025-06-27 00:00:00	2
2	1	https://res.cloudinary.com/dylr6druv/image/upload/v1751038883/beige_cmzgwu.jpg	2025-06-27 00:00:00	2025-06-27 00:00:00	1
3	5	https://res.cloudinary.com/dylr6druv/image/upload/v1751905302/productos/491460875_18348658399154808_451698371882816615_n_686bf415107aa.jpg	2025-07-07 11:21:43.47314	2025-07-07 11:21:43.47314	1
4	6	https://res.cloudinary.com/dylr6druv/image/upload/v1751944261/productos/508341905_18355062649154808_5609957379163578152_n_686c8c3ff1d22.jpg	2025-07-07 22:11:03.105828	2025-07-07 22:11:03.105828	1
6	7	https://res.cloudinary.com/dylr6druv/image/upload/v1752163632/productos/505754203_18355062628154808_5546480840874827680_n_686fe52d8a306.jpg	2025-07-10 11:07:15.866358	2025-07-10 11:07:15.866358	1
7	4	https://res.cloudinary.com/dylr6druv/image/upload/v1752163963/productos/500087737_18352630294154808_331607580300684470_n_686fe67dabe3e.jpg	2025-07-10 11:12:47.26325	2025-07-10 11:12:47.26325	1
\.


--
-- TOC entry 5125 (class 0 OID 24766)
-- Dependencies: 231
-- Data for Name: ingreso; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.ingreso (id, id_producto, ref, precio_costo, precio_costo_igv, precio_venta, utilidad_esperada, utilidad_neta, cantidad, fecha_ingreso, id_usuario, id_sucursal) FROM stdin;
2	1	784849	2000.00	2360.00	47.20	832.20	472.20	60	2025-06-22 00:00:00	1	5
4	2	174849	2300.00	2714.00	40.70	956.00	542.40	80	2025-06-23 00:00:00	1	5
6	2	1748474	2300.00	2714.00	40.70	956.00	542.40	80	2025-06-23 00:00:00	1	5
9	3	233232	300.00	354.00	28.32	124.80	70.80	15	2025-07-03 00:00:00	1	5
11	5	78484921	5000.00	2900.00	348.00	2080.00	1180.00	10	2025-07-07 00:00:00	1	6
12	6	78484923	2900.00	3422.00	273.76	1206.45	684.45	15	2025-07-08 00:00:00	1	7
13	7	784847	2900.00	3422.00	410.64	1206.40	684.40	10	2025-07-08 00:00:00	1	7
14	4	2323412	2900.00	3422.00	41.06	1206.00	684.00	100	2025-07-10 00:00:00	1	6
\.


--
-- TOC entry 5123 (class 0 OID 24733)
-- Dependencies: 229
-- Data for Name: inventario_sucursal; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.inventario_sucursal (id_producto, id_sucursal, cantidad, fecha_actualizacion, estado, ref) FROM stdin;
2	7	40	\N	CUADRA	1
2	6	20	\N	FALTA	2
2	5	20	\N	SOBRA	3
3	5	0	2025-07-03	CUADRA	4
3	6	15	2025-07-03	CUADRA	5
4	5	0	2025-07-03	CUADRA	6
5	6	0	2025-07-07	CUADRA	8
5	7	10	2025-07-07	CUADRA	9
6	7	15	2025-07-07	CUADRA	10
7	7	10	2025-07-07	CUADRA	11
4	6	65	2025-07-10	CUADRA	7
4	7	50	2025-07-10	CUADRA	12
\.


--
-- TOC entry 5133 (class 0 OID 41025)
-- Dependencies: 239
-- Data for Name: items_venta; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.items_venta (id, venta_id, producto_id, cantidad, precio_unitario) FROM stdin;
\.


--
-- TOC entry 5122 (class 0 OID 24694)
-- Dependencies: 228
-- Data for Name: kardex; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.kardex (id_kardex, id_producto, cantidad, tipo_movimiento, precio_costo, fecha_movimiento, id_usuario) FROM stdin;
1	1	60	INGRESO	33.33	2025-06-22 00:00:00	1
2	2	80	INGRESO	28.75	2025-06-23 00:00:00	1
3	2	40	SALIDA	2300.00	2025-06-23 00:00:00	1
4	2	40	INGRESO	2300.00	2025-06-23 00:00:00	1
5	2	20	SALIDA	2300.00	2025-06-23 00:00:00	1
6	2	20	INGRESO	2300.00	2025-06-23 00:00:00	1
7	2	40	SALIDA	0.00	2025-06-23 00:00:00	1
8	2	40	INGRESO	2300.00	2025-06-23 00:00:00	1
9	2	40	SALIDA	2300.00	2025-06-23 00:00:00	1
10	2	40	INGRESO	2300.00	2025-06-23 00:00:00	1
11	4	15	INGRESO	20.00	2025-07-03 00:00:00	1
12	3	15	INGRESO	20.00	2025-07-03 00:00:00	1
13	3	15	SALIDA	0.00	2025-07-04 00:00:00	1
14	3	15	INGRESO	0.00	2025-07-04 00:00:00	1
15	4	15	INGRESO	20.00	2025-07-04 00:00:00	1
16	4	15	SALIDA	0.00	2025-07-04 00:00:00	1
17	4	15	INGRESO	0.00	2025-07-04 00:00:00	1
18	5	10	INGRESO	500.00	2025-07-07 00:00:00	1
19	5	10	SALIDA	0.00	2025-07-07 00:00:00	1
20	5	10	INGRESO	0.00	2025-07-07 00:00:00	1
21	6	15	INGRESO	193.33	2025-07-08 00:00:00	1
22	7	10	INGRESO	290.00	2025-07-08 00:00:00	1
23	4	100	INGRESO	29.00	2025-07-10 00:00:00	1
24	4	50	SALIDA	0.00	2025-07-10 00:00:00	1
25	4	50	INGRESO	0.00	2025-07-10 00:00:00	1
\.


--
-- TOC entry 5120 (class 0 OID 24661)
-- Dependencies: 226
-- Data for Name: producto; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.producto (id_producto, ref_producto, nombre_producto, descripcion_producto, id_subcategoria, talla_producto, creado_en, actualizado_en, estado) FROM stdin;
2	41983627	Polo Azul Brave Boxy Fit/Oversize/ Unisex		2	M	2025-06-21 12:48:01.059083	2025-07-02 11:02:29.185185	t
3	90000620	Pantalón Super baggy jean	Pantalones alta calidad. Con reactivo Con proceso de Lavado para darle suavidad y detalles. 100% algodón denim rigido 10 a 14OZ.	9	L	2025-07-02 11:15:17.945665	2025-07-02 11:15:17.945665	t
4	46100566	Pantalón Super baggy jean	Pantalones alta calidad. Con reactivo Con proceso de Lavado para darle suavidad y detalles. 100% algodón denim rigido 10 a 14OZ.	9	XL	2025-07-02 11:17:39.42427	2025-07-02 11:17:39.42427	t
1	034545	POLO BOXY FIT PREMIUM 20/1 BEIGE		2	S	2025-06-21 12:36:58.579986	2025-07-02 11:02:11.161634	f
5	51349041	ADIDAS HOOPS 1.0	ADIDAS HOOPS 1.0	12	L	2025-07-07 09:22:17.068498	2025-07-07 09:22:17.068498	t
6	44020190	ADIDAS GRAND COURT ALPHA 00s	ADIDAS GRAND COURT ALPHA 00s	12	L	2025-07-07 22:10:37.861147	2025-07-07 22:10:37.861147	t
7	76355609	ADIDAS HOOPS 2.0	ADIDAS HOOPS 2.0	12	M	2025-07-07 22:14:56.055368	2025-07-07 22:14:56.055368	t
\.


--
-- TOC entry 5118 (class 0 OID 24632)
-- Dependencies: 224
-- Data for Name: subcategoria; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.subcategoria (id_subcategoria, nombre_subcategoria, descripcion_subcategoria, id_categoria, creado_en, actualizado_en, estado) FROM stdin;
2	Boxyfit	Sudaderas anchas y rectas	3	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
3	Slimfit	Sudaderas ce¤idas al cuerpo	3	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
4	Oversize	Sudaderas grandes y c¢modas	3	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
5	Con Capucha	Con estilo hoodie cl sico	3	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
6	Sin Capucha	Dise¤o tipo crewneck	3	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
7	Jogger Cargo	Pantalones con bolsillos	4	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
8	Jogger Cl sico	Dise¤o urbano tradicional	4	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
10	Cargo Slim	Pantalones cargo ajustados	4	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
13	Chunky	Sneakers de suela gruesa	10	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
14	Skate Shoes	Zapatillas estilo skate	10	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
15	Bucket Hat	Sombrero tipo pescador	11	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
16	Snapback	Gorra con visera plana	11	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
17	Trucker	Gorra con malla trasera	11	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
18	Dad Hat	Gorra con visera curva	11	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
19	Cadenas	Collares y cadenas urbanas	9	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
20	Pulseras	Brazaletes estilo callejero	9	2025-06-30 21:42:14.222357	2025-06-30 21:42:14.222357	t
22	Rinoneras	Bolsos cruzados urbanos	9	2025-06-30 21:42:14.222357	2025-06-30 21:48:21.407147	t
21	Lentes	Gafas oscuras y de diseno	9	2025-06-30 21:42:14.222357	2025-06-30 21:48:48.740383	t
1	Boxyfit	Anchos	1	2025-06-21 11:54:10.231324	2025-06-28 23:24:46.836153	f
24	Boxyfit	ewew	5	2025-06-30 22:41:06.026684	2025-06-30 22:41:06.026684	f
23	Boxyfit	ewew	9	2025-06-30 22:33:42.79853	2025-06-30 22:33:42.79853	f
25	Boxyfit	ds	5	2025-06-30 22:44:13.142331	2025-06-30 22:44:13.142331	f
9	Baggy	Estilo urbano	2	2025-06-30 21:42:14.222357	2025-07-02 11:13:43.358507	t
26	Boxyfit	dsasd	5	2025-07-03 19:30:36.052129	2025-07-03 19:30:36.052129	t
12	Sneakers Bajos	Zapatillas de corte bajo	12	2025-06-30 21:42:14.222357	2025-07-10 10:47:13.453815	t
11	Sneakers Altos	Zapatillas de ca¤a alta	12	2025-06-30 21:42:14.222357	2025-07-10 10:47:34.477647	t
\.


--
-- TOC entry 5116 (class 0 OID 24596)
-- Dependencies: 222
-- Data for Name: sucursal; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.sucursal (id_sucursal, nombre_sucursal, tipo_sucursal, direccion_sucursal, creado_en, actualizado_en, estado_sucursal, id_supervisor) FROM stdin;
5	Centro de Distribucion FreeStyle-Shop	almacen	Av. Echenique 145, Huacho 15135	2025-06-22 19:24:11.604843	2025-06-22 19:24:11.604843	t	1
6	Tienda FreestyleShop	fisica	Colon 486, Huacho 15136	2025-06-22 19:26:59.219397	2025-06-22 19:26:59.219397	t	2
7	Tienda Online	online	WEB	2025-06-22 19:27:44.544377	2025-06-22 19:27:44.544377	t	3
\.


--
-- TOC entry 5127 (class 0 OID 24788)
-- Dependencies: 233
-- Data for Name: transferencia; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.transferencia (id, id_producto, id_sucursal_origen, id_sucursal_destino, cantidad, fecha_transferencia, id_usuario) FROM stdin;
1	2	5	7	40	2025-06-23 00:00:00	1
2	2	5	6	20	2025-06-23 00:00:00	1
3	2	7	5	40	2025-06-23 00:00:00	1
4	2	5	7	40	2025-06-23 00:00:00	1
5	3	5	6	15	2025-07-04 00:00:00	1
6	3	5	6	15	2025-07-04 00:00:00	1
7	4	5	6	15	2025-07-04 00:00:00	1
8	5	6	7	10	2025-07-07 00:00:00	1
9	4	6	7	50	2025-07-10 00:00:00	1
\.


--
-- TOC entry 5112 (class 0 OID 16401)
-- Dependencies: 218
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.usuario (id_usuario, ref_usuario, nombre_usuario, email_usuario, pass_usuario, telefono_usuario, direccion_usuario, rol_usuario, estado_usuario, creado_en, actualizado_en) FROM stdin;
2	rios2	Jhon Rios	ngx@gmail.com	$2y$10$xpwUCh11E8S0GTj4c626jOnQyKbxTRWG6oC9QSu4Z2nzkiYypfE1W	753159	Huacho	admin	t	2025-06-16 02:47:07	2025-06-15 19:47:07.672336
1	rios	Jeancarlos Daniel Rios	wtke90@gmail.com	$2y$10$ZZcplKzLsSkVVv0uxTd87O7xMYyX5Qh9vLO3QY.M/5xBFiIJ/iAwq	946228564	Huacho	admin	t	2025-06-16 02:40:18	2025-06-15 19:40:18.465099
3	daniscarft	Daniella	dani@gmail.com	$2y$10$dwkiBHnBo6zTnrXhjeKMKulKlc0QR6M51Ig0aTbZa9ZTbmlRI1F2.	946789741	Huacho	admin	t	2025-06-21 16:36:33	2025-06-21 09:36:33.854516
4	calitos	Carlos	cjsl@gmail.com	$2y$10$IiVw26v0ArQAqHnSaiS8BOPacSITeYsTXYTJq4BfeqE19wpskPFRC	946228564	Huacho	cliente	t	2025-06-30 16:30:57	2025-06-30 09:30:57.981402
\.


--
-- TOC entry 5131 (class 0 OID 41004)
-- Dependencies: 237
-- Data for Name: ventas; Type: TABLE DATA; Schema: public; Owner: developer
--

COPY public.ventas (id, ref, usuario_id, sucursal_id, estado, creado_en, actualizado_en, metodo_pago, total, estado_pago) FROM stdin;
\.


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 250
-- Name: carrito_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.carrito_id_seq', 1, true);


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 252
-- Name: carrito_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.carrito_items_id_seq', 14, true);


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 219
-- Name: categoria_id_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.categoria_id_categoria_seq', 16, true);


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 244
-- Name: conteos_ciclicos_id_conteo_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.conteos_ciclicos_id_conteo_seq', 12, true);


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 242
-- Name: detalles_envio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.detalles_envio_id_seq', 1, false);


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 240
-- Name: envios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.envios_id_seq', 1, false);


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 234
-- Name: imagenes_producto_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.imagenes_producto_id_seq', 7, true);


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 230
-- Name: ingreso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.ingreso_id_seq', 14, true);


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 248
-- Name: ingreso_ref_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.ingreso_ref_seq', 78484922, true);


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 249
-- Name: inventario_sucursal_ref_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.inventario_sucursal_ref_seq', 12, true);


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 238
-- Name: items_venta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.items_venta_id_seq', 1, false);


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 227
-- Name: kardex_id_kardex_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.kardex_id_kardex_seq', 25, true);


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 225
-- Name: producto_id_producto_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.producto_id_producto_seq', 7, true);


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 246
-- Name: producto_vista_cliente_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.producto_vista_cliente_id_seq', 10, true);


--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 223
-- Name: subcategoria_id_subcategoria_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.subcategoria_id_subcategoria_seq', 26, true);


--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 221
-- Name: sucursales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.sucursales_id_seq', 7, true);


--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 232
-- Name: transferencia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.transferencia_id_seq', 9, true);


--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 217
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 4, true);


--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 236
-- Name: ventas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: developer
--

SELECT pg_catalog.setval('public.ventas_id_seq', 1, false);


--
-- TOC entry 4931 (class 2606 OID 65701)
-- Name: carrito_items carrito_items_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito_items
    ADD CONSTRAINT carrito_items_pkey PRIMARY KEY (id);


--
-- TOC entry 4927 (class 2606 OID 65687)
-- Name: carrito carrito_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT carrito_pkey PRIMARY KEY (id);


--
-- TOC entry 4883 (class 2606 OID 24592)
-- Name: categoria categoria_nombre_categoria_key; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_nombre_categoria_key UNIQUE (nombre_categoria);


--
-- TOC entry 4885 (class 2606 OID 24590)
-- Name: categoria categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id_categoria);


--
-- TOC entry 4923 (class 2606 OID 41129)
-- Name: conteos_ciclicos conteos_ciclicos_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos
    ADD CONSTRAINT conteos_ciclicos_pkey PRIMARY KEY (id_conteo);


--
-- TOC entry 4921 (class 2606 OID 41074)
-- Name: detalles_envio detalles_envio_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.detalles_envio
    ADD CONSTRAINT detalles_envio_pkey PRIMARY KEY (id);


--
-- TOC entry 4919 (class 2606 OID 41050)
-- Name: envios envios_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.envios
    ADD CONSTRAINT envios_pkey PRIMARY KEY (id);


--
-- TOC entry 4911 (class 2606 OID 32792)
-- Name: imagenes_producto imagenes_producto_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.imagenes_producto
    ADD CONSTRAINT imagenes_producto_pkey PRIMARY KEY (id);


--
-- TOC entry 4905 (class 2606 OID 24773)
-- Name: ingreso ingreso_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT ingreso_pkey PRIMARY KEY (id);


--
-- TOC entry 4900 (class 2606 OID 24738)
-- Name: inventario_sucursal inventario_sucursal_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.inventario_sucursal
    ADD CONSTRAINT inventario_sucursal_pkey PRIMARY KEY (id_producto, id_sucursal);


--
-- TOC entry 4902 (class 2606 OID 57503)
-- Name: inventario_sucursal inventario_sucursal_ref_unique; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.inventario_sucursal
    ADD CONSTRAINT inventario_sucursal_ref_unique UNIQUE (ref);


--
-- TOC entry 4917 (class 2606 OID 41030)
-- Name: items_venta items_venta_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.items_venta
    ADD CONSTRAINT items_venta_pkey PRIMARY KEY (id);


--
-- TOC entry 4897 (class 2606 OID 24700)
-- Name: kardex kardex_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.kardex
    ADD CONSTRAINT kardex_pkey PRIMARY KEY (id_kardex);


--
-- TOC entry 4891 (class 2606 OID 24670)
-- Name: producto producto_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_pkey PRIMARY KEY (id_producto);


--
-- TOC entry 4893 (class 2606 OID 24672)
-- Name: producto producto_ref_producto_key; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_ref_producto_key UNIQUE (ref_producto);


--
-- TOC entry 4925 (class 2606 OID 49313)
-- Name: catalogo_productos producto_vista_cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.catalogo_productos
    ADD CONSTRAINT producto_vista_cliente_pkey PRIMARY KEY (id);


--
-- TOC entry 4895 (class 2606 OID 24679)
-- Name: producto ref_producto_unico; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT ref_producto_unico UNIQUE (ref_producto);


--
-- TOC entry 4907 (class 2606 OID 57492)
-- Name: ingreso ref_unica_global; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT ref_unica_global UNIQUE (ref);


--
-- TOC entry 4889 (class 2606 OID 24641)
-- Name: subcategoria subcategoria_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.subcategoria
    ADD CONSTRAINT subcategoria_pkey PRIMARY KEY (id_subcategoria);


--
-- TOC entry 4887 (class 2606 OID 24606)
-- Name: sucursal sucursales_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT sucursales_pkey PRIMARY KEY (id_sucursal);


--
-- TOC entry 4909 (class 2606 OID 24794)
-- Name: transferencia transferencia_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia
    ADD CONSTRAINT transferencia_pkey PRIMARY KEY (id);


--
-- TOC entry 4879 (class 2606 OID 16410)
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 4881 (class 2606 OID 16412)
-- Name: usuario usuario_ref_usuario_key; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_ref_usuario_key UNIQUE (ref_usuario);


--
-- TOC entry 4913 (class 2606 OID 41011)
-- Name: ventas ventas_pkey; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_pkey PRIMARY KEY (id);


--
-- TOC entry 4915 (class 2606 OID 41013)
-- Name: ventas ventas_ref_key; Type: CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_ref_key UNIQUE (ref);


--
-- TOC entry 4928 (class 1259 OID 65713)
-- Name: idx_carrito_session; Type: INDEX; Schema: public; Owner: developer
--

CREATE INDEX idx_carrito_session ON public.carrito USING btree (session_id);


--
-- TOC entry 4929 (class 1259 OID 65712)
-- Name: idx_carrito_usuario; Type: INDEX; Schema: public; Owner: developer
--

CREATE INDEX idx_carrito_usuario ON public.carrito USING btree (usuario_id);


--
-- TOC entry 4903 (class 1259 OID 57493)
-- Name: idx_ingreso_ref; Type: INDEX; Schema: public; Owner: developer
--

CREATE INDEX idx_ingreso_ref ON public.ingreso USING btree (ref);


--
-- TOC entry 4898 (class 1259 OID 57504)
-- Name: idx_inventario_sucursal_ref; Type: INDEX; Schema: public; Owner: developer
--

CREATE INDEX idx_inventario_sucursal_ref ON public.inventario_sucursal USING btree (ref);


--
-- TOC entry 4932 (class 1259 OID 65714)
-- Name: idx_items_carrito; Type: INDEX; Schema: public; Owner: developer
--

CREATE INDEX idx_items_carrito ON public.carrito_items USING btree (carrito_id);


--
-- TOC entry 4965 (class 2620 OID 24594)
-- Name: categoria trigger_actualizar_categoria; Type: TRIGGER; Schema: public; Owner: developer
--

CREATE TRIGGER trigger_actualizar_categoria BEFORE UPDATE ON public.categoria FOR EACH ROW EXECUTE FUNCTION public.actualizar_timestamp();


--
-- TOC entry 4963 (class 2606 OID 65702)
-- Name: carrito_items carrito_items_carrito_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito_items
    ADD CONSTRAINT carrito_items_carrito_id_fkey FOREIGN KEY (carrito_id) REFERENCES public.carrito(id) ON DELETE CASCADE;


--
-- TOC entry 4964 (class 2606 OID 65707)
-- Name: carrito_items carrito_items_producto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito_items
    ADD CONSTRAINT carrito_items_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES public.producto(id_producto) ON DELETE CASCADE;


--
-- TOC entry 4962 (class 2606 OID 65688)
-- Name: carrito carrito_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.carrito
    ADD CONSTRAINT carrito_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuario(id_usuario) ON DELETE CASCADE;


--
-- TOC entry 4955 (class 2606 OID 41130)
-- Name: conteos_ciclicos conteos_ciclicos_producto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos
    ADD CONSTRAINT conteos_ciclicos_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES public.producto(id_producto) ON DELETE CASCADE;


--
-- TOC entry 4956 (class 2606 OID 41145)
-- Name: conteos_ciclicos conteos_ciclicos_producto_id_sucursal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos
    ADD CONSTRAINT conteos_ciclicos_producto_id_sucursal_id_fkey FOREIGN KEY (producto_id, sucursal_id) REFERENCES public.inventario_sucursal(id_producto, id_sucursal) ON DELETE CASCADE;


--
-- TOC entry 4957 (class 2606 OID 41135)
-- Name: conteos_ciclicos conteos_ciclicos_sucursal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos
    ADD CONSTRAINT conteos_ciclicos_sucursal_id_fkey FOREIGN KEY (sucursal_id) REFERENCES public.sucursal(id_sucursal) ON DELETE CASCADE;


--
-- TOC entry 4958 (class 2606 OID 41140)
-- Name: conteos_ciclicos conteos_ciclicos_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.conteos_ciclicos
    ADD CONSTRAINT conteos_ciclicos_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuario(id_usuario) ON DELETE CASCADE;


--
-- TOC entry 4953 (class 2606 OID 41075)
-- Name: detalles_envio detalles_envio_envio_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.detalles_envio
    ADD CONSTRAINT detalles_envio_envio_id_fkey FOREIGN KEY (envio_id) REFERENCES public.envios(id);


--
-- TOC entry 4954 (class 2606 OID 41080)
-- Name: detalles_envio detalles_envio_producto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.detalles_envio
    ADD CONSTRAINT detalles_envio_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES public.producto(id_producto);


--
-- TOC entry 4952 (class 2606 OID 41051)
-- Name: envios envios_venta_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.envios
    ADD CONSTRAINT envios_venta_id_fkey FOREIGN KEY (venta_id) REFERENCES public.ventas(id);


--
-- TOC entry 4940 (class 2606 OID 24776)
-- Name: ingreso fk_producto_ingreso; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT fk_producto_ingreso FOREIGN KEY (id_producto) REFERENCES public.producto(id_producto);


--
-- TOC entry 4938 (class 2606 OID 24739)
-- Name: inventario_sucursal fk_producto_inventario; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.inventario_sucursal
    ADD CONSTRAINT fk_producto_inventario FOREIGN KEY (id_producto) REFERENCES public.producto(id_producto);


--
-- TOC entry 4936 (class 2606 OID 24701)
-- Name: kardex fk_producto_kardex_general; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.kardex
    ADD CONSTRAINT fk_producto_kardex_general FOREIGN KEY (id_producto) REFERENCES public.producto(id_producto);


--
-- TOC entry 4943 (class 2606 OID 24795)
-- Name: transferencia fk_producto_transferencia; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia
    ADD CONSTRAINT fk_producto_transferencia FOREIGN KEY (id_producto) REFERENCES public.producto(id_producto);


--
-- TOC entry 4944 (class 2606 OID 24805)
-- Name: transferencia fk_sucursal_destino; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia
    ADD CONSTRAINT fk_sucursal_destino FOREIGN KEY (id_sucursal_destino) REFERENCES public.sucursal(id_sucursal);


--
-- TOC entry 4941 (class 2606 OID 24820)
-- Name: ingreso fk_sucursal_ingreso; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT fk_sucursal_ingreso FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);


--
-- TOC entry 4939 (class 2606 OID 24744)
-- Name: inventario_sucursal fk_sucursal_inventario; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.inventario_sucursal
    ADD CONSTRAINT fk_sucursal_inventario FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);


--
-- TOC entry 4945 (class 2606 OID 24800)
-- Name: transferencia fk_sucursal_origen; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia
    ADD CONSTRAINT fk_sucursal_origen FOREIGN KEY (id_sucursal_origen) REFERENCES public.sucursal(id_sucursal);


--
-- TOC entry 4933 (class 2606 OID 24815)
-- Name: sucursal fk_supervisor; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT fk_supervisor FOREIGN KEY (id_supervisor) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4942 (class 2606 OID 24781)
-- Name: ingreso fk_usuario_ingreso; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT fk_usuario_ingreso FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4937 (class 2606 OID 24706)
-- Name: kardex fk_usuario_kardex_general; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.kardex
    ADD CONSTRAINT fk_usuario_kardex_general FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4946 (class 2606 OID 24810)
-- Name: transferencia fk_usuario_transferencia; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.transferencia
    ADD CONSTRAINT fk_usuario_transferencia FOREIGN KEY (id_usuario) REFERENCES public.usuario(id_usuario);


--
-- TOC entry 4947 (class 2606 OID 32793)
-- Name: imagenes_producto imagenes_producto_producto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.imagenes_producto
    ADD CONSTRAINT imagenes_producto_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES public.producto(id_producto);


--
-- TOC entry 4950 (class 2606 OID 41036)
-- Name: items_venta items_venta_producto_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.items_venta
    ADD CONSTRAINT items_venta_producto_id_fkey FOREIGN KEY (producto_id) REFERENCES public.producto(id_producto);


--
-- TOC entry 4951 (class 2606 OID 41031)
-- Name: items_venta items_venta_venta_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.items_venta
    ADD CONSTRAINT items_venta_venta_id_fkey FOREIGN KEY (venta_id) REFERENCES public.ventas(id);


--
-- TOC entry 4935 (class 2606 OID 24673)
-- Name: producto producto_id_subcategoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.producto
    ADD CONSTRAINT producto_id_subcategoria_fkey FOREIGN KEY (id_subcategoria) REFERENCES public.subcategoria(id_subcategoria) ON DELETE SET NULL;


--
-- TOC entry 4959 (class 2606 OID 49324)
-- Name: catalogo_productos producto_vista_cliente_imagen_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.catalogo_productos
    ADD CONSTRAINT producto_vista_cliente_imagen_id_fkey FOREIGN KEY (imagen_id) REFERENCES public.imagenes_producto(id) ON DELETE CASCADE;


--
-- TOC entry 4960 (class 2606 OID 49319)
-- Name: catalogo_productos producto_vista_cliente_ingreso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.catalogo_productos
    ADD CONSTRAINT producto_vista_cliente_ingreso_id_fkey FOREIGN KEY (ingreso_id) REFERENCES public.ingreso(id) ON DELETE CASCADE;


--
-- TOC entry 4961 (class 2606 OID 49314)
-- Name: catalogo_productos producto_vista_cliente_producto_id_sucursal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.catalogo_productos
    ADD CONSTRAINT producto_vista_cliente_producto_id_sucursal_id_fkey FOREIGN KEY (producto_id, sucursal_id) REFERENCES public.inventario_sucursal(id_producto, id_sucursal) ON DELETE CASCADE;


--
-- TOC entry 4934 (class 2606 OID 24642)
-- Name: subcategoria subcategoria_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.subcategoria
    ADD CONSTRAINT subcategoria_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES public.categoria(id_categoria) ON DELETE CASCADE;


--
-- TOC entry 4948 (class 2606 OID 41019)
-- Name: ventas ventas_sucursal_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_sucursal_id_fkey FOREIGN KEY (sucursal_id) REFERENCES public.sucursal(id_sucursal);


--
-- TOC entry 4949 (class 2606 OID 41014)
-- Name: ventas ventas_usuario_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: developer
--

ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuario(id_usuario);


-- Completed on 2025-07-10 14:15:58

--
-- PostgreSQL database dump complete
--

