CREATE 
TRIGGER QUKI.BEFORE_DELETE_USERS_GROUPS
	BEFORE DELETE
	ON QUKI.USERS_GROUPS
	FOR EACH ROW
BEGIN
    DELETE FROM `USERS_FRIENDS_IN_GROUPS`
    WHERE USERS_FRIENDS_IN_GROUPS.group_id=OLD.group_id;
END