SELECT
    `User`,
    `Host`
FROM
    mysql.user
WHERE
    `Super_priv` = 'Y' OR `Grant_priv` = 'Y';