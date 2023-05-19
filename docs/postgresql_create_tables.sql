CREATE TABLE users (
    id          serial         PRIMARY KEY,
    firstname   varchar(256)   NOT NULL,
    lastname    varchar(256)   NOT NULL,
    email       varchar(256)   NOT NULL,
    password    varchar(2048)  NOT NULL,
    created_at  timestamp      DEFAULT NOW(),
    updated_at  timestamp      DEFAULT NOW(),
    CONSTRAINT  uniq_email     UNIQUE(email),
  	CONSTRAINT  not_empty      CHECK(email <> '')
);

CREATE TABLE articles (
    id           serial PRIMARY KEY,
    header_text  varchar(120)  NOT NULL,
    header_img   varchar(60),
    small_text   varchar(255)  NOT NULL,
    full_text    text          NOT NULL,
    article_img  varchar(60),
    created_at   timestamp     DEFAULT NOW(),
    updated_at   timestamp     DEFAULT NOW()
);

CREATE TABLE news (
    id          serial        PRIMARY KEY,
    header      varchar(120)  NOT NULL,
    short_text  varchar(255)  NOT NULL,
    text        text          NOT NULL,
    news_img    varchar(60),
    created_at  timestamp     DEFAULT NOW(),
    updated_at  timestamp     DEFAULT NOW()
);

CREATE TABLE history (
    id          serial     PRIMARY KEY,
    tstamp      timestamp  DEFAULT NOW(),
    schemaname  text,
    tabname     text,
    operation   text,
    who         text       DEFAULT current_user,
    new_val     jsonb,
    old_val     jsonb
);

CREATE TABLE items (
    id          serial        PRIMARY KEY,
    name        varchar(255),
    phone       varchar(15),
    key         varchar(25)   NOT NULL,
    created_at  timestamp     DEFAULT NOW(),
    updated_at  timestamp     DEFAULT NOW()
);

CREATE OR REPLACE FUNCTION trigger_set_updated()
  RETURNS TRIGGER AS $$
  BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
  END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION change_trigger()
  RETURNS trigger AS $$
  BEGIN
    IF     TG_OP = 'INSERT'
    THEN
      INSERT INTO history (tabname, schemaname, operation, new_val)
        VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, row_to_json(NEW));
      RETURN NEW;
    ELSIF  TG_OP = 'UPDATE'
    THEN
      INSERT INTO history (tabname, schemaname, operation, new_val, old_val)
        VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP,
          row_to_json(NEW), row_to_json(OLD));
      RETURN NEW;
    ELSIF  TG_OP = 'DELETE'
    THEN
      INSERT INTO history (tabname, schemaname, operation, old_val)
        VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, row_to_json(OLD));
      RETURN OLD;
    END IF;
  END;
$$ LANGUAGE 'plpgsql' SECURITY DEFINER;

CREATE TRIGGER set_updated
  BEFORE UPDATE ON users
  FOR EACH ROW
  EXECUTE PROCEDURE trigger_set_updated();

CREATE TRIGGER set_updated
  BEFORE UPDATE ON articles
  FOR EACH ROW
  EXECUTE PROCEDURE trigger_set_updated();

CREATE TRIGGER set_updated
  BEFORE UPDATE ON news
  FOR EACH ROW
  EXECUTE PROCEDURE trigger_set_updated();

CREATE TRIGGER set_updated
  BEFORE UPDATE ON items
  FOR EACH ROW
  EXECUTE PROCEDURE trigger_set_updated();

CREATE TRIGGER t_history
  AFTER INSERT OR UPDATE OR DELETE ON users
  FOR EACH ROW
  EXECUTE PROCEDURE change_trigger();

CREATE TRIGGER t_history
  AFTER INSERT OR UPDATE OR DELETE ON items
  FOR EACH ROW
  EXECUTE PROCEDURE change_trigger();
  