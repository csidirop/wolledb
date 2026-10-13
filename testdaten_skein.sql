-- Testdaten für tx_wdb_domain_model_skein
-- Voraussetzungen:
--   1. Schema ist aktuell:       ddev typo3 database:updateschema
--   2. testdaten_yarn.sql wurde importiert (die Garne werden über ihren Namen gefunden)
-- Ordner-ID anpassen, falls dein Datensatz-Ordner nicht die ID 50 hat (gleicher Ordner wie die Garne):
SET @pid = 50;

SET @wiese  = (SELECT uid FROM tx_wdb_domain_model_yarn WHERE name = 'Wiesenwolle Fein'       AND deleted = 0 LIMIT 1);
SET @sommer = (SELECT uid FROM tx_wdb_domain_model_yarn WHERE name = 'Sommerbaumwolle'        AND deleted = 0 LIMIT 1);
SET @alpaka = (SELECT uid FROM tx_wdb_domain_model_yarn WHERE name = 'Alpaka Wolke'           AND deleted = 0 LIMIT 1);
SET @seide  = (SELECT uid FROM tx_wdb_domain_model_yarn WHERE name = 'Seidenhauch'            AND deleted = 0 LIMIT 1);
SET @socke  = (SELECT uid FROM tx_wdb_domain_model_yarn WHERE name = 'Sockenwolle Regenbogen' AND deleted = 0 LIMIT 1);

INSERT INTO tx_wdb_domain_model_skein
    (pid, tstamp, crdate, hidden, deleted, yarn, color, initial_weight, current_weight)
VALUES
    -- Wiesenwolle Fein: 3 x 100 g schwarz (eines angebraucht), 1 x 200 g blau
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @wiese,  'schwarz', 100, 100),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @wiese,  'schwarz', 100, 100),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @wiese,  'schwarz', 100,  62),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @wiese,  'blau',    200, 200),
    -- Sommerbaumwolle: 2 x weiß, 1 x gelb (verbraucht, wird nicht mitgezählt)
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @sommer, 'weiß',     50,  50),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @sommer, 'weiß',     50,  50),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @sommer, 'gelb',     50,   0),
    -- Alpaka Wolke
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @alpaka, 'grau',    100, 100),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @alpaka, 'grau',    100,  45),
    -- Seidenhauch
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @seide,  'natur',    50,  50),
    -- Sockenwolle Regenbogen
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @socke,  'bunt',    100, 100),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, @socke,  'bunt',    100, 100);

-- Zähler-Spalte "skeins" am Garn aktualisieren (Anzahl der Knäuel, wie sie TYPO3 selbst pflegt)
UPDATE tx_wdb_domain_model_yarn y
SET y.skeins = (
    SELECT COUNT(*) FROM tx_wdb_domain_model_skein s WHERE s.yarn = y.uid AND s.deleted = 0
);
