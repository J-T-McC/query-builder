DROP TABLE IF EXISTS `projects`;

CREATE TABLE "projects" (
	"id"	INTEGER NOT NULL,
	"project_name"	TEXT NOT NULL,
	"user_id"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);