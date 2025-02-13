PGDMP      8                 }         	   ecommerce    17.2    17.2 >    i           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            j           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            k           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            l           1262    20423 	   ecommerce    DATABASE     |   CREATE DATABASE ecommerce WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'French_France.1252';
    DROP DATABASE ecommerce;
                     postgres    false                        3079    20424    pgcrypto 	   EXTENSION     <   CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;
    DROP EXTENSION pgcrypto;
                        false            m           0    0    EXTENSION pgcrypto    COMMENT     <   COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';
                             false    2            �            1259    20462    articles    TABLE     ]  CREATE TABLE public.articles (
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
    DROP TABLE public.articles;
       public         heap r       postgres    false            �            1259    20461    articles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.articles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.articles_id_seq;
       public               postgres    false    219            n           0    0    articles_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;
          public               postgres    false    218            �            1259    20483    commande_details    TABLE     �   CREATE TABLE public.commande_details (
    id integer NOT NULL,
    commande_id integer,
    article_id integer,
    quantity integer NOT NULL,
    price numeric(10,2) NOT NULL
);
 $   DROP TABLE public.commande_details;
       public         heap r       postgres    false            �            1259    20482    commande_details_id_seq    SEQUENCE     �   CREATE SEQUENCE public.commande_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.commande_details_id_seq;
       public               postgres    false    223            o           0    0    commande_details_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.commande_details_id_seq OWNED BY public.commande_details.id;
          public               postgres    false    222            �            1259    20474 	   commandes    TABLE     g  CREATE TABLE public.commandes (
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
    DROP TABLE public.commandes;
       public         heap r       postgres    false            �            1259    20473    commandes_id_seq    SEQUENCE     �   CREATE SEQUENCE public.commandes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.commandes_id_seq;
       public               postgres    false    221            p           0    0    commandes_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.commandes_id_seq OWNED BY public.commandes.id;
          public               postgres    false    220            �            1259    20544    promo_codes    TABLE     �  CREATE TABLE public.promo_codes (
    id integer NOT NULL,
    code character varying(50) NOT NULL,
    discount_percentage numeric(5,2),
    active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT promo_codes_discount_percentage_check CHECK (((discount_percentage > (0)::numeric) AND (discount_percentage <= (100)::numeric)))
);
    DROP TABLE public.promo_codes;
       public         heap r       postgres    false            �            1259    20543    promo_codes_id_seq    SEQUENCE     �   CREATE SEQUENCE public.promo_codes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.promo_codes_id_seq;
       public               postgres    false    231            q           0    0    promo_codes_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.promo_codes_id_seq OWNED BY public.promo_codes.id;
          public               postgres    false    230            �            1259    20523    reviews    TABLE     !  CREATE TABLE public.reviews (
    id integer NOT NULL,
    article_id integer,
    user_id integer,
    rating integer,
    comment text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);
    DROP TABLE public.reviews;
       public         heap r       postgres    false            �            1259    20522    reviews_id_seq    SEQUENCE     �   CREATE SEQUENCE public.reviews_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.reviews_id_seq;
       public               postgres    false    229            r           0    0    reviews_id_seq    SEQUENCE OWNED BY     A   ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;
          public               postgres    false    228            �            1259    20500    stats    TABLE     �   CREATE TABLE public.stats (
    id integer NOT NULL,
    category character varying(100) NOT NULL,
    date date NOT NULL,
    orders integer DEFAULT 0,
    revenue numeric(10,2) DEFAULT 0
);
    DROP TABLE public.stats;
       public         heap r       postgres    false            �            1259    20499    stats_id_seq    SEQUENCE     �   CREATE SEQUENCE public.stats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.stats_id_seq;
       public               postgres    false    225            s           0    0    stats_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.stats_id_seq OWNED BY public.stats.id;
          public               postgres    false    224            �            1259    20509    utilisateurs    TABLE     =  CREATE TABLE public.utilisateurs (
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
     DROP TABLE public.utilisateurs;
       public         heap r       postgres    false            �            1259    20508    utilisateurs_id_seq    SEQUENCE     �   CREATE SEQUENCE public.utilisateurs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.utilisateurs_id_seq;
       public               postgres    false    227            t           0    0    utilisateurs_id_seq    SEQUENCE OWNED BY     P   ALTER SEQUENCE public.utilisateurs_id_seq OWNED BY public.utilisateurs.user_id;
          public               postgres    false    226            �           2604    20465    articles id    DEFAULT     j   ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);
 :   ALTER TABLE public.articles ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    218    219    219            �           2604    20486    commande_details id    DEFAULT     z   ALTER TABLE ONLY public.commande_details ALTER COLUMN id SET DEFAULT nextval('public.commande_details_id_seq'::regclass);
 B   ALTER TABLE public.commande_details ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    223    223            �           2604    20477    commandes id    DEFAULT     l   ALTER TABLE ONLY public.commandes ALTER COLUMN id SET DEFAULT nextval('public.commandes_id_seq'::regclass);
 ;   ALTER TABLE public.commandes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    221    221            �           2604    20547    promo_codes id    DEFAULT     p   ALTER TABLE ONLY public.promo_codes ALTER COLUMN id SET DEFAULT nextval('public.promo_codes_id_seq'::regclass);
 =   ALTER TABLE public.promo_codes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    230    231    231            �           2604    20526 
   reviews id    DEFAULT     h   ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);
 9   ALTER TABLE public.reviews ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    229    228    229            �           2604    20503    stats id    DEFAULT     d   ALTER TABLE ONLY public.stats ALTER COLUMN id SET DEFAULT nextval('public.stats_id_seq'::regclass);
 7   ALTER TABLE public.stats ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    224    225    225            �           2604    20512    utilisateurs user_id    DEFAULT     w   ALTER TABLE ONLY public.utilisateurs ALTER COLUMN user_id SET DEFAULT nextval('public.utilisateurs_id_seq'::regclass);
 C   ALTER TABLE public.utilisateurs ALTER COLUMN user_id DROP DEFAULT;
       public               postgres    false    227    226    227            Z          0    20462    articles 
   TABLE DATA           p   COPY public.articles (id, name, category, price, stock, description, created_at, updated_at, image) FROM stdin;
    public               postgres    false    219   �K       ^          0    20483    commande_details 
   TABLE DATA           X   COPY public.commande_details (id, commande_id, article_id, quantity, price) FROM stdin;
    public               postgres    false    223   �M       \          0    20474 	   commandes 
   TABLE DATA              COPY public.commandes (id, user_name, status, total_price, created_at, updated_at, address, phone_number, user_id) FROM stdin;
    public               postgres    false    221   �M       f          0    20544    promo_codes 
   TABLE DATA           X   COPY public.promo_codes (id, code, discount_percentage, active, created_at) FROM stdin;
    public               postgres    false    231   gN       d          0    20523    reviews 
   TABLE DATA           W   COPY public.reviews (id, article_id, user_id, rating, comment, created_at) FROM stdin;
    public               postgres    false    229   �N       `          0    20500    stats 
   TABLE DATA           D   COPY public.stats (id, category, date, orders, revenue) FROM stdin;
    public               postgres    false    225   �N       b          0    20509    utilisateurs 
   TABLE DATA           �   COPY public.utilisateurs (user_id, nom, email, password, role, created_at, etat, address, phone_number, last_login, updated_at, prenom) FROM stdin;
    public               postgres    false    227   YO       u           0    0    articles_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.articles_id_seq', 1, false);
          public               postgres    false    218            v           0    0    commande_details_id_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.commande_details_id_seq', 4, true);
          public               postgres    false    222            w           0    0    commandes_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.commandes_id_seq', 1, false);
          public               postgres    false    220            x           0    0    promo_codes_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.promo_codes_id_seq', 1, false);
          public               postgres    false    230            y           0    0    reviews_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.reviews_id_seq', 1, false);
          public               postgres    false    228            z           0    0    stats_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.stats_id_seq', 10, true);
          public               postgres    false    224            {           0    0    utilisateurs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.utilisateurs_id_seq', 4, true);
          public               postgres    false    226            �           2606    20472    articles articles_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.articles DROP CONSTRAINT articles_pkey;
       public                 postgres    false    219            �           2606    20488 &   commande_details commande_details_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY public.commande_details DROP CONSTRAINT commande_details_pkey;
       public                 postgres    false    223            �           2606    20481    commandes commandes_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT commandes_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.commandes DROP CONSTRAINT commandes_pkey;
       public                 postgres    false    221            �           2606    20554     promo_codes promo_codes_code_key 
   CONSTRAINT     [   ALTER TABLE ONLY public.promo_codes
    ADD CONSTRAINT promo_codes_code_key UNIQUE (code);
 J   ALTER TABLE ONLY public.promo_codes DROP CONSTRAINT promo_codes_code_key;
       public                 postgres    false    231            �           2606    20552    promo_codes promo_codes_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.promo_codes
    ADD CONSTRAINT promo_codes_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.promo_codes DROP CONSTRAINT promo_codes_pkey;
       public                 postgres    false    231            �           2606    20532    reviews reviews_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_pkey;
       public                 postgres    false    229            �           2606    20507    stats stats_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.stats
    ADD CONSTRAINT stats_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.stats DROP CONSTRAINT stats_pkey;
       public                 postgres    false    225            �           2606    20520 #   utilisateurs utilisateurs_email_key 
   CONSTRAINT     _   ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_email_key UNIQUE (email);
 M   ALTER TABLE ONLY public.utilisateurs DROP CONSTRAINT utilisateurs_email_key;
       public                 postgres    false    227            �           2606    20518    utilisateurs utilisateurs_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY public.utilisateurs
    ADD CONSTRAINT utilisateurs_pkey PRIMARY KEY (user_id);
 H   ALTER TABLE ONLY public.utilisateurs DROP CONSTRAINT utilisateurs_pkey;
       public                 postgres    false    227            �           2606    20494 1   commande_details commande_details_article_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_article_id_fkey FOREIGN KEY (article_id) REFERENCES public.articles(id) ON DELETE CASCADE;
 [   ALTER TABLE ONLY public.commande_details DROP CONSTRAINT commande_details_article_id_fkey;
       public               postgres    false    223    219    4786            �           2606    20489 2   commande_details commande_details_commande_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.commande_details
    ADD CONSTRAINT commande_details_commande_id_fkey FOREIGN KEY (commande_id) REFERENCES public.commandes(id) ON DELETE CASCADE;
 \   ALTER TABLE ONLY public.commande_details DROP CONSTRAINT commande_details_commande_id_fkey;
       public               postgres    false    221    223    4788            �           2606    20558    commandes fk_user_id    FK CONSTRAINT        ALTER TABLE ONLY public.commandes
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.utilisateurs(user_id);
 >   ALTER TABLE ONLY public.commandes DROP CONSTRAINT fk_user_id;
       public               postgres    false    221    4796    227            �           2606    20533    reviews reviews_article_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_article_id_fkey FOREIGN KEY (article_id) REFERENCES public.articles(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_article_id_fkey;
       public               postgres    false    219    4786    229            �           2606    20538    reviews reviews_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.utilisateurs(user_id) ON DELETE CASCADE;
 F   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_user_id_fkey;
       public               postgres    false    229    227    4796            Z   �  x�}�Mn�0���)�1$Ŋ���E��-*H�lhjl��8*|�ޢ[�{]�#
�� ����͛�E�H�=Y����cPGV��(���r)�"O�Y��1@���M� � /����>��%w8EV�7Y��y[��/�B|�K����R��=�F��󥴳��K7ѡ���n��1@��>.r�\�|��X��uG��i�R��B�I;�"J�/��S}{0�T���t�޹�x��v�}&��Z[�B��7G�<2��Ne2��DE���j;E�Q9i�;���y1�T����r�|���V��k�=��|x��l�,;�e����@�DU�dS�\��!>R�6|5zh����ߡ��i>��g��wȫx�y +�)�7L�}���oR����aG_c�Ѿw�=������/�䁹���o�Ĳ�.u���e`٢k0�{��|�DÜ�g�O���4�a^����?�R0T      ^   4   x�3�4400�A3KK=KK.#��1H"b1�4��EL@"��jb���� Ϋ�      \   }   x�3400�����Sp�O�H�K��K細�Գ��4202�50�50��"#.C#N�ļT���̒Nǂ�����NC#�.K�.Sd&D�1H�1�cNfr��SQ~ygPjVjr	H�%A�&\1z\\\ ��*      f      x������ � �      d      x������ � �      `   �   x�u�1�0�پU��M2r 6F�R)�"�Cp..F܉*��!�����z������P{@b��!z�0� �8�=���D��֊�@(=���V����sz��Q�９
���~�0��VQ���,��^�4�ʽ�9Y�h8���e.5/��&�x�'AP�      b   m  x���Ko�@���)<xu�W���V�E��[�����EED?}5m/��L&�2��/�Y��At�����B
��4ɱ�Q�/�[U���k�>�{b2�����co31G�z#Q�Wԁ}.���9@a-�[�l`jQl!��F%�|���-�id!�b:� ���<�p� +���L�k�Q����?x�\Oy��<��d���))'����D!��8T�Y!BoA"�"/���04tDu���Y�Qd�rVQ.���A+����5�eU?'ln���O���;����uC��U׈��r;�٦���U���20��kR"�W?�k.�4��ԬR���t���鬓�8d��w���y����:�Nj_p�'�4��۹�     