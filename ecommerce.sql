--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2

-- Started on 2025-01-17 11:11:31

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
-- TOC entry 2 (class 3079 OID 20424)
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- TOC entry 4972 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 20462)
-- Name: articles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.articles (
    id integer NOT NULL,
    name character varying(150) NOT NULL,
    category character varying(100) NOT NULL,
    price numeric(10,2) NOT NULL,
    stock integer DEFAULT 0,
    description text,
    created_at date DEFAULT CURRENT_DATE,
    updated_at date DEFAULT CURRENT_DATE,
    image character varying(255)
);


ALTER TABLE public.articles OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 20461)
-- Name: articles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.articles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.articles_id_seq OWNER TO postgres;

--
-- TOC entry 4973 (class 0 OID 0)
-- Dependencies: 218
-- Name: articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;


--
-- TOC entry 223 (class 1259 OID 20483)
-- Name: commande_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commande_details (
    id integer NOT NULL,
    commande_id integer,
    article_id integer,
    quantity integer NOT NULL,
    price numeric(10,2) NOT NULL
);


ALTER TABLE public.commande_details OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 20482)
-- Name: commande_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commande_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.commande_details_id_seq OWNER TO postgres;

--
-- TOC entry 4974 (class 0 OID 0)
-- Dependencies: 222
-- Name: commande_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.commande_details_id_seq OWNED BY public.commande_details.id;


--
-- TOC entry 221 (class 1259 OID 20474)
-- Name: commandes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.commandes (
    id integer NOT NULL,
    user_name character varying(150) NOT NULL,
    status character varying(20) DEFAULT 'Pending'::character varying,
    total_price numeric(10,2) NOT NULL,
    created_at date DEFAULT CURRENT_DATE,
    updated_at date,
    address text,
    phone_number character varying(15),
    user_id integer
);


ALTER TABLE public.commandes OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 20473)
-- Name: commandes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.commandes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.commandes_id_seq OWNER TO postgres;

--
-- TOC entry 4975 (class 0 OID 0)
-- Dependencies: 220
-- Name: commandes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.commandes_id_seq OWNED BY public.commandes.id;


--
-- TOC entry 231 (class 1259 OID 20544)
-- Name: promo_codes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.promo_codes (
    id integer NOT NULL,
    code character varying(50) NOT NULL,
    discount_percentage numeric(5,2),
    active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT promo_codes_discount_percentage_check CHECK (((discount_percentage > (0)::numeric) AND (discount_percentage <= (100)::numeric)))
);


ALTER TABLE public.promo_codes OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 20543)
-- Name: promo_codes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.promo_codes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.promo_codes_id_seq OWNER TO postgres;

--
-- TOC entry 4976 (class 0 OID 0)
-- Dependencies: 230
-- Name: promo_codes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.promo_codes_id_seq OWNED BY public.promo_codes.id;


--
-- TOC entry 229 (class 1259 OID 20523)
-- Name: reviews; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reviews (
    id integer NOT NULL,
    article_id integer,
    user_id integer,
    rating integer,
    comment text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);


ALTER TABLE public.reviews OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 20522)
-- Name: reviews_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reviews_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reviews_id_seq OWNER TO postgres;

--
-- TOC entry 4977 (class 0 OID 0)
-- Dependencies: 228
-- Name: reviews_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;


--
-- TOC entry 225 (class 1259 OID 20500)
-- Name: stats; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.stats (
    id integer NOT NULL,
    category character varying(100) NOT NULL,
    date date NOT NULL,
    orders integer DEFAULT 0,
    revenue numeric(10,2) DEFAULT 0
);


ALTER TABLE public.stats OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 20499)
-- Name: stats_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.stats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.stats_id_seq OWNER TO postgres;

--
-- TOC entry 4978 (class 0 OID 0)
-- Dependencies: 224
-- Name: stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.stats_id_seq OWNED BY public.stats.id;


--
-- TOC entry 227 (class 1259 OID 20509)
-- Name: utilisateurs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utilisateurs (
    user_id integer NOT NULL,
    nom character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(20) DEFAULT 'client'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    etat boolean DEFAULT true,
    address text,
    phone_number character varying(15),
    last_login timestamp without time zone,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    prenom character varying(255)
);


ALTER TABLE public.utilisateurs OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 20508)
-- Name: utilisateurs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utilisateurs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utilisateurs_id_seq OWNER TO postgres;

--
-- TOC entry 4979 (class 0 OID 0)
-- Dependencies: 226
-- Name: utilisateurs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utilisateurs_id_seq OWNED BY public.utilisateurs.user_id;


--
-- TOC entry 4762 (class 2604 OID 20465)
-- Name: articles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);


--
-- TOC entry 4769 (class 2604 OID 20486)
-- Name: commande_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_details ALTER COLUMN id SET DEFAULT nextval('public.commande_details_id_seq'::regclass);


--
-- TOC entry 4766 (class 2604 OID 20477)
-- Name: commandes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commandes ALTER COLUMN id SET DEFAULT nextval('public.commandes_id_seq'::regclass);


--
-- TOC entry 4780 (class 2604 OID 20547)
-- Name: promo_codes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.promo_codes ALTER COLUMN id SET DEFAULT nextval('public.promo_codes_id_seq'::regclass);


--
-- TOC entry 4778 (class 2604 OID 20526)
-- Name: reviews id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);


--
-- TOC entry 4770 (class 2604 OID 20503)
-- Name: stats id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats ALTER COLUMN id SET DEFAULT nextval('public.stats_id_seq'::regclass);


--
-- TOC entry 4773 (class 2604 OID 20512)
-- Name: utilisateurs user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateurs ALTER COLUMN user_id SET DEFAULT nextval('public.utilisateurs_id_seq'::regclass);


--
-- TOC entry 4954 (class 0 OID 20462)
-- Dependencies: 219
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.articles (id, name, category, price, stock, description, created_at, updated_at, image) FROM stdin;
1	Smartphone XYZ	Électronique	699.99	120	Un smartphone haut de gamme avec 128 Go de stockage.	2025-01-01	2025-01-05	\N
2	Chaise ergonomique	Mobilier	129.99	45	Chaise de bureau confortable et ajustable.	2025-01-02	2025-01-05	\N
3	Montre connectée Alpha	Accessoires	199.99	78	Montre connectée avec moniteur de santé intégré.	2025-01-03	2025-01-05	\N
4	Ordinateur portable Pro X	Électronique	1299.99	50	Un ordinateur portable ultra-fin avec un écran Retina et 512 Go de stockage SSD.	2025-01-07	2025-01-07	\N
5	Casque Audio Bass Boost	Accessoires	149.99	100	Casque audio sans fil avec réduction de bruit active et basses puissantes.	2025-01-07	2025-01-07	\N
6	Lampe LED Intelligente	Maison	29.99	200	Lampe LED connectée avec contrôle vocal et couleurs personnalisables.	2025-01-07	2025-01-07	\N
7	Sac à dos antivol	Accessoires	59.99	80	Sac à dos sécurisé avec fermetures dissimulées et port USB intégré.	2025-01-07	2025-01-07	\N
\.


--
-- TOC entry 4958 (class 0 OID 20483)
-- Dependencies: 223
-- Data for Name: commande_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commande_details (id, commande_id, article_id, quantity, price) FROM stdin;
1	1001	1	1	699.99
2	1001	3	1	199.99
3	1002	2	1	129.99
4	1003	3	1	199.99
\.


--
-- TOC entry 4956 (class 0 OID 20474)
-- Dependencies: 221
-- Data for Name: commandes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.commandes (id, user_name, status, total_price, created_at, updated_at, address, phone_number, user_id) FROM stdin;
1001	John Doe	Pending	899.98	2025-01-04	\N	\N	\N	2
1002	Jane Smith	Approved	129.99	2025-01-05	2025-01-05	\N	\N	3
1003	Alice Brown	Rejected	199.99	2025-01-05	2025-01-05	\N	\N	4
\.


--
-- TOC entry 4966 (class 0 OID 20544)
-- Dependencies: 231
-- Data for Name: promo_codes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.promo_codes (id, code, discount_percentage, active, created_at) FROM stdin;
\.


--
-- TOC entry 4964 (class 0 OID 20523)
-- Dependencies: 229
-- Data for Name: reviews; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reviews (id, article_id, user_id, rating, comment, created_at) FROM stdin;
\.


--
-- TOC entry 4960 (class 0 OID 20500)
-- Dependencies: 225
-- Data for Name: stats; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.stats (id, category, date, orders, revenue) FROM stdin;
1	Traffic	2025-01-01	150	1029.97
2	Traffic	2025-01-02	175	1299.94
3	Traffic	2025-01-03	200	1599.91
4	Traffic	2025-01-04	250	1999.88
5	Traffic	2025-01-05	300	1299.95
6	Électronique	2025-01-01	1	699.99
7	Électronique	2025-01-02	2	1399.98
8	Électronique	2025-01-03	3	2099.97
9	Mobilier	2025-01-02	1	129.99
10	Accessoires	2025-01-03	4	799.96
\.


--
-- TOC entry 4962 (class 0 OID 20509)
-- Dependencies: 227
-- Data for Name: utilisateurs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utilisateurs (user_id, nom, email, password, role, created_at, etat, address, phone_number, last_login, updated_at, prenom) FROM stdin;
1	Admin	admin@example.com	$2y$10$XhnFJDvvUp5aGykXWfbEYeRQaXwLZIbLqR9PeETLPexuj3B.IXeMm	admin	2025-01-09 13:31:05.374083	t	\N	\N	\N	2025-01-16 20:07:57.605141	\N
2	Doe	john.doe@example.com	$2y$10$PpmSSAnM2OJVmDLoLzMlzubcs1uZZ2tROhzFH3.1xTbTowdpeTLr.	client	2025-01-09 13:32:51.870378	t	\N	\N	\N	2025-01-16 20:07:57.605141	John
3	Smith	jane.smith@example.com	$2a$06$9Mx2w49PfTg/8qcWgU4eoeasCGTHRm3yysJTRalXy9B4BpC3/ctQe	client	2025-01-09 13:32:51.870378	t	\N	\N	\N	2025-01-16 20:07:57.605141	Jane
4	Brown	alice.brown@example.com	$2a$06$X8z1vRc5Qoe/a5x.oG279OTnTHCZyddb4V/fxnmnPcve8IThpKXki	client	2025-01-09 13:32:51.870378	t	\N	\N	\N	2025-01-16 20:07:57.605141	Alice
\.


--
-- TOC entry 4980 (class 0 OID 0)
-- Dependencies: 218
-- Name: articles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.articles_id_seq', 1, false);


--
-- TOC entry 4981 (class 0 OID 0)
-- Dependencies: 222
-- Name: commande_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.commande_details_id_seq', 4, true);


--
-- TOC entry 4982 (class 0 OID 0)
-- Dependencies: 220
-- Name: commandes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.commandes_id_seq', 1, false);


--
-- TOC entry 4983 (class 0 OID 0)
-- Dependencies: 230
-- Name: promo_codes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.promo_codes_id_seq', 1, false);


--
-- TOC entry 4984 (class 0 OID 0)
-- Dependencies: 228
-- Name: reviews_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reviews_id_seq', 1, false);


--
-- TOC entry 4985 (class 0 OID 0)
-- Dependencies: 224
-- Name: stats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.stats_id_seq', 10, true);


--
-- TOC entry 4986 (class 0 OID 0)
-- Dependencies: 226
-- Name: utilisateurs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utilisateurs_id_seq', 4, true);


--
-- TOC entry 4786 (class 2606 OID 20472)
-- Name: articles articles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);


--
-- TOC entry 4790 (class 2606 OID 20488)
-- Name: commande_details commande_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_pkey PRIMARY KEY (id);


--
-- TOC entry 4788 (class 2606 OID 20481)
-- Name: commandes commandes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT commandes_pkey PRIMARY KEY (id);


--
-- TOC entry 4800 (class 2606 OID 20554)
-- Name: promo_codes promo_codes_code_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.promo_codes
    ADD CONSTRAINT promo_codes_code_key UNIQUE (code);


--
-- TOC entry 4802 (class 2606 OID 20552)
-- Name: promo_codes promo_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.promo_codes
    ADD CONSTRAINT promo_codes_pkey PRIMARY KEY (id);


--
-- TOC entry 4798 (class 2606 OID 20532)
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);


--
-- TOC entry 4792 (class 2606 OID 20507)
-- Name: stats stats_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.stats
    ADD CONSTRAINT stats_pkey PRIMARY KEY (id);


--
-- TOC entry 4794 (class 2606 OID 20520)
-- Name: utilisateurs utilisateurs_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_email_key UNIQUE (email);


--
-- TOC entry 4796 (class 2606 OID 20518)
-- Name: utilisateurs utilisateurs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_pkey PRIMARY KEY (user_id);


--
-- TOC entry 4804 (class 2606 OID 20494)
-- Name: commande_details commande_details_article_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_article_id_fkey FOREIGN KEY (article_id) REFERENCES public.articles(id) ON DELETE CASCADE;


--
-- TOC entry 4805 (class 2606 OID 20489)
-- Name: commande_details commande_details_commande_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_commande_id_fkey FOREIGN KEY (commande_id) REFERENCES public.commandes(id) ON DELETE CASCADE;


--
-- TOC entry 4803 (class 2606 OID 20558)
-- Name: commandes fk_user_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.utilisateurs(user_id);


--
-- TOC entry 4806 (class 2606 OID 20533)
-- Name: reviews reviews_article_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_article_id_fkey FOREIGN KEY (article_id) REFERENCES public.articles(id) ON DELETE CASCADE;


--
-- TOC entry 4807 (class 2606 OID 20538)
-- Name: reviews reviews_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.utilisateurs(user_id) ON DELETE CASCADE;


-- Completed on 2025-01-17 11:11:31

--
-- PostgreSQL database dump complete
--

