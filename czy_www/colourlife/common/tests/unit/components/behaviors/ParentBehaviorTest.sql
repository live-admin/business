CREATE TABLE "table"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "parent_id" INTEGER NOT NULL DEFAULT 0
);

INSERT INTO "main"."table" VALUES (1, 'row-1', 0);
INSERT INTO "main"."table" VALUES (2, 'row-1.1', 1);
INSERT INTO "main"."table" VALUES (3, 'row-1.2', 1);
INSERT INTO "main"."table" VALUES (4, 'row-1.1.1', 2);
INSERT INTO "main"."table" VALUES (5, 'row-1.1.2', 2);
INSERT INTO "main"."table" VALUES (6, 'row-1.2.1', 3);
INSERT INTO "main"."table" VALUES (7, 'row-1.2.2', 3);

CREATE TABLE "table_with_deleted"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "parent_id" INTEGER NOT NULL DEFAULT 0,
  "is_deleted" TINYINT NOT NULL DEFAULT 0
);

INSERT INTO "main"."table_with_deleted" VALUES (1, 'row-1', 0, 0);
INSERT INTO "main"."table_with_deleted" VALUES (2, 'row-1.1', 1, 0);
INSERT INTO "main"."table_with_deleted" VALUES (3, 'row-1.2', 1, 0);
INSERT INTO "main"."table_with_deleted" VALUES (4, 'row-1.1.1', 2, 0);
INSERT INTO "main"."table_with_deleted" VALUES (5, 'row-1.1.2', 2, 0);
INSERT INTO "main"."table_with_deleted" VALUES (6, 'row-1.2.1', 3, 0);
INSERT INTO "main"."table_with_deleted" VALUES (7, 'row-1.2.2', 3, 1);
INSERT INTO "main"."table_with_deleted" VALUES (8, 'row-1.1.1.1', 4, 0);
INSERT INTO "main"."table_with_deleted" VALUES (9, 'row-1.1.1.2', 4, 0);
INSERT INTO "main"."table_with_deleted" VALUES (10, 'row-1.1.2.1', 5, 0);
INSERT INTO "main"."table_with_deleted" VALUES (11, 'row-1.1.2.2', 5, 1);
INSERT INTO "main"."table_with_deleted" VALUES (12, 'row-1.2.1.1', 6, 1);
INSERT INTO "main"."table_with_deleted" VALUES (13, 'row-1.2.1.2', 6, 1);
INSERT INTO "main"."table_with_deleted" VALUES (14, 'row-1.2.2.1', 7, 1);
INSERT INTO "main"."table_with_deleted" VALUES (15, 'row-1.2.2.2', 7, 1);

CREATE TABLE "table_with_state"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "parent_id" INTEGER NOT NULL DEFAULT 0,
  "state" TINYINT NOT NULL DEFAULT 0
);

INSERT INTO "main"."table_with_state" VALUES (1, 'row-1', 0, 0);
INSERT INTO "main"."table_with_state" VALUES (2, 'row-1.1', 1, 0);
INSERT INTO "main"."table_with_state" VALUES (3, 'row-1.2', 1, 0);
INSERT INTO "main"."table_with_state" VALUES (4, 'row-1.1.1', 2, 0);
INSERT INTO "main"."table_with_state" VALUES (5, 'row-1.1.2', 2, 0);
INSERT INTO "main"."table_with_state" VALUES (6, 'row-1.2.1', 3, 0);
INSERT INTO "main"."table_with_state" VALUES (7, 'row-1.2.2', 3, 1);
INSERT INTO "main"."table_with_state" VALUES (8, 'row-1.1.1.1', 4, 0);
INSERT INTO "main"."table_with_state" VALUES (9, 'row-1.1.1.2', 4, 0);
INSERT INTO "main"."table_with_state" VALUES (10, 'row-1.1.2.1', 5, 0);
INSERT INTO "main"."table_with_state" VALUES (11, 'row-1.1.2.2', 5, 1);
INSERT INTO "main"."table_with_state" VALUES (12, 'row-1.2.1.1', 6, 1);
INSERT INTO "main"."table_with_state" VALUES (13, 'row-1.2.1.2', 6, 1);
INSERT INTO "main"."table_with_state" VALUES (14, 'row-1.2.2.1', 7, 1);
INSERT INTO "main"."table_with_state" VALUES (15, 'row-1.2.2.2', 7, 1);

CREATE TABLE "table_with_state_and_deleted"
(
  "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  "title" TEXT NOT NULL,
  "parent_id" INTEGER NOT NULL DEFAULT 0,
  "state" TINYINT NOT NULL DEFAULT 0,
  "is_deleted" TINYINT NOT NULL DEFAULT 0
);

INSERT INTO "main"."table_with_state_and_deleted" VALUES (1, 'row-1', 0, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (2, 'row-1.1', 1, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (3, 'row-1.2', 1, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (4, 'row-1.1.1', 2, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (5, 'row-1.1.2', 2, 1, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (6, 'row-1.2.1', 3, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (7, 'row-1.2.2', 3, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (8, 'row-1.1.1.1', 4, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (9, 'row-1.1.1.2', 4, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (10, 'row-1.1.2.1', 5, 1, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (11, 'row-1.1.2.2', 5, 1, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (12, 'row-1.2.1.1', 6, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (13, 'row-1.2.1.2', 6, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (14, 'row-1.2.2.1', 7, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (15, 'row-1.2.2.2', 7, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (16, 'row-1.1.1.1.1', 8, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (17, 'row-1.1.1.1.2', 8, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (18, 'row-1.1.1.2.1', 9, 0, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (19, 'row-1.1.1.2.2', 9, 1, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (20, 'row-1.1.2.1.1', 10, 1, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (21, 'row-1.1.2.1.2', 10, 1, 0);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (22, 'row-1.1.2.2.1', 11, 1, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (23, 'row-1.1.2.2.2', 11, 1, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (24, 'row-1.2.1.1.1', 12, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (25, 'row-1.2.1.1.2', 12, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (26, 'row-1.2.1.2.1', 13, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (27, 'row-1.2.1.2.2', 13, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (28, 'row-1.2.2.1.1', 14, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (29, 'row-1.2.2.1.2', 14, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (30, 'row-1.2.2.2.1', 15, 0, 1);
INSERT INTO "main"."table_with_state_and_deleted" VALUES (31, 'row-1.2.2.2.2', 15, 0, 1);
