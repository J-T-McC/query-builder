-- delete vs truncate to accommodate sqlite
DELETE FROM `projects`;
INSERT INTO `projects` VALUES (1, 'Project 1 - user 1', 1);
INSERT INTO `projects` VALUES (2, 'Project 2  - user 1', 1);
INSERT INTO `projects` VALUES (3, 'Project 3  - user 1', 1);
INSERT INTO `projects` VALUES (4, 'Project 4  - user 1', 1);
INSERT INTO `projects` VALUES (5, 'Project 1 - user 2', 2);
INSERT INTO `projects` VALUES (6, 'Project 2  - user 2', 2);
INSERT INTO `projects` VALUES (7, 'Project 3  - user 2', 2);
INSERT INTO `projects` VALUES (8, 'Project 4  - user 2', 2);
INSERT INTO `projects` VALUES (9, 'Project 1 - user 3', 3);
INSERT INTO `projects` VALUES (10, 'Project 2  - user 3', 3);
INSERT INTO `projects` VALUES (11, 'Project 3  - user 3', 3);
INSERT INTO `projects` VALUES (12, 'Project 4  - user 3', 3);
INSERT INTO `projects` VALUES (13, 'Project 1 - user 4', 4);
INSERT INTO `projects` VALUES (14, 'Project 2  - user 4', 4);
INSERT INTO `projects` VALUES (15, 'Project 3  - user 4', 4);
INSERT INTO `projects` VALUES (16, 'Project 4  - user 4', 4);