CREATE TABLE "table"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "state" TINYINT NOT NULL DEFAULT 0
);

INSERT INTO "main"."table" VALUES (1, 'row-1', 0);
INSERT INTO "main"."table" VALUES (2, 'row-2', 1);
INSERT INTO "main"."table" VALUES (3, 'row-3', 1);
INSERT INTO "main"."table" VALUES (4, 'row-4', 0);
INSERT INTO "main"."table" VALUES (5, 'row-5', 0);
