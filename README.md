`START TRANSACTION;

CREATE TABLE \`users\` (
  \`id\` int(11) NOT NULL,
  \`email\` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  \`passwort\` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  \`vorname\` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  \`nachname\` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  \`created_at\` timestamp NOT NULL DEFAULT current_timestamp(),
  \`updated_at\` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE \`vm\` (
  \`id\` int(11) NOT NULL,
  \`name\` varchar(255) NOT NULL,
  \`userid\` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE \`users\`
  ADD PRIMARY KEY (\`id\`),
  ADD UNIQUE KEY \`email\` (\`email\`);
  
ALTER TABLE \`vm\`
  ADD PRIMARY KEY (\`id\`);
  
ALTER TABLE \`users\`
  MODIFY \`id\` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE \`vm\`
  MODIFY \`id\` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;`
