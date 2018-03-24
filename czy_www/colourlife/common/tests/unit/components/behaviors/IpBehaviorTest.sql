CREATE TABLE "table"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "created_at" VARCHAR(15) NOT NULL DEFAULT '',
  "updated_at" VARCHAR(15) NOT NULL DEFAULT ''
);

INSERT INTO "main"."table" VALUES (1, 'row-1', '192.168.1.1', '192.168.1.1');
INSERT INTO "main"."table" VALUES (2, 'row-2', '192.168.1.2', '192.168.1.2');
INSERT INTO "main"."table" VALUES (3, 'row-3', '192.168.1.3', '192.168.1.3');
INSERT INTO "main"."table" VALUES (4, 'row-4', '192.168.1.4', '192.168.1.4');
INSERT INTO "main"."table" VALUES (5, 'row-5', '192.168.1.5', '192.168.1.5');
INSERT INTO "main"."table" VALUES (6, 'row-6', '192.168.1.6', '192.168.1.6');
