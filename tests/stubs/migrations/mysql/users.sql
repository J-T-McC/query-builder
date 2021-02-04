SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

INSERT INTO `users` VALUES (1, 'test-1@example.ca', 'Test 1');
INSERT INTO `users` VALUES (2, 'test-2@example.ca', 'Test 2');
INSERT INTO `users` VALUES (3, 'test-3@example.ca', 'Test 3');
INSERT INTO `users` VALUES (4, 'test-4@example.ca', 'Test 4');
INSERT INTO `users` VALUES (5, 'test-5@example.ca', 'Test 5');
INSERT INTO `users` VALUES (6, 'test-6@example.ca', 'Test 6');

SET FOREIGN_KEY_CHECKS = 1;
