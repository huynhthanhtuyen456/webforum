<?php
/**
* Define DB Table Name as constants, it can be reused in the prepared SQL statements.
*/
namespace MVC\Helpers;


class Constants 
{
    public static string $USER_TABLE = "User";
    public static string $ROLE_TABLE = "Role";
    public static string $PERMISSION_TABLE = "Permission";
    public static string $USER_ROLE_TABLE = "UserRole";
    public static string $ROLE_PERMISSION_TABLE = "RolePermission";
    
    public static string $QUESTION_TABLE = "Question";
    public static string $ANSWER_TABLE = "Answer";
    
    public static string $MODULE_TABLE = "Module";
    public static string $CONTACT_TABLE = "Contact";
}
?>