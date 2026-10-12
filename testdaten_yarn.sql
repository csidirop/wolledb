-- Testdaten für tx_wdb_domain_model_yarn
-- Ordner-ID anpassen, falls dein Datensatz-Ordner nicht die ID 50 hat:
SET @pid = 50;

INSERT INTO tx_wdb_domain_model_yarn
    (pid, tstamp, crdate, hidden, deleted, name, hook_size, needle_size, yardage, mainfiber, fibercomposition, yarn_weight, source)
VALUES
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Wiesenwolle Fein',      '2.5 mm', '2.5 mm', 420, 'wool',      '100% Merinowolle',                  'fingering',   'Wollladen Mannheim'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Sommerbaumwolle',       '3.0 mm', '3.0 mm', 280, 'cotton',    '100% Baumwolle',                    'sport',       'Online-Shop'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Alpaka Wolke',          '4.5 mm', '4.5 mm', 220, 'alpaca',    '80% Alpaka, 20% Nylon',             'dk',          'Handarbeitsmesse'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Seidenhauch',           '2.0 mm', '2.0 mm', 700, 'silk',      '70% Seide, 30% Kidmohair',          'lace',        'Online-Shop'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Grobstrick Classic',    '6.0 mm', '6.0 mm', 130, 'wool',      '100% Schurwolle',                   'bulky',       'Wollladen Mannheim'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Amigurumi Soft',        '2.5 mm', '3.0 mm', 290, 'acrylic',   '100% Polyacryl',                    'sport',       'Bastelmarkt'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Leinen Natur',          '3.5 mm', '3.5 mm', 240, 'linen',     '100% Leinen',                       'dk',          'Online-Shop'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Bambus Glanz',          '3.0 mm', '3.5 mm', 260, 'bamboo',    '60% Bambus, 40% Baumwolle',         'dk',          'Handarbeitsmesse'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Sockenwolle Regenbogen','2.5 mm', '2.5 mm', 420, 'wool',      '75% Schurwolle, 25% Polyamid',      'fingering',   'Wollladen Mannheim'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Kaschmir Traum',        '3.5 mm', '3.5 mm', 300, 'cashmere',  '100% Kaschmir',                     'sport',       'Online-Shop'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Hanf Robust',           '4.0 mm', '4.0 mm', 200, 'hemp',      '100% Hanf',                         'aran',        'Naturmarkt'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Riesenwolle XXL',       '12 mm',  '12 mm',  100, 'wool',      '100% Merinowolle',                  'super_bulky', 'Online-Shop'),
    (@pid, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0, 0, 'Mix Allrounder',        '4.0 mm', '4.0 mm', 190, 'other',     '50% Baumwolle, 50% Polyacryl',      'aran',        'Bastelmarkt');
