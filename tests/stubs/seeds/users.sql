-- delete vs truncate to accommodate sqlite
DELETE FROM `users`;
INSERT INTO `users` VALUES (1, 'test-1@example.ca', 'Test 1');
INSERT INTO `users` VALUES (2, 'test-2@example.ca', 'Test 2');
INSERT INTO `users` VALUES (3, 'test-3@example.ca', 'Test 3');
INSERT INTO `users` VALUES (4, 'test-4@example.ca', 'Test 4');
INSERT INTO `users` VALUES (5, 'test-5@example.ca', 'Test 5');
INSERT INTO `users` VALUES (6, 'test-6@example.ca', 'Test 6');