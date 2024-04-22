<?php
/**
* Define DB Table Name as constants, it can be reused in the prepared SQL statements.
*/
namespace MVC\Helpers;

class Permissions
{
    public static string $ADD_QUESTION = "addQuestion";
    public static string $GET_QUESTION = "getQuestion";
    public static string $EDIT_QUESTION = "editQuestion";
    public static string $DELETE_QUESTION = "deleteQuestion";

    public static string $ADD_ANSWER = "addAnswer";
    public static string $GET_ANSWER = "getAnswer";
    public static string $EDIT_ANSWER = "editAnswer";
    public static string $DELETE_ANSWER = "deleteAnswer";

    public static string $ADD_CONTACT = "addContact";
    public static string $GET_CONTACT = "getContact";
    public static string $EDIT_CONTACT = "editContact";
    public static string $DELETE_CONTACT = "deleteContact";

    public static string $ADD_USER = "addUser";
    public static string $GET_USER = "getUser";
    public static string $EDIT_USER = "editUser";
    public static string $DELETE_USER = "deleteUser";

    public static string $ADD_PERMISSION = "addPermission";
    public static string $GET_PERMISSION = "getPermission";
    public static string $EDIT_PERMISSION = "editPermission";
    public static string $DELETE_PERMISSION = "deletePermission";

    public static string $ADD_ROLE = "addRole";
    public static string $GET_ROLE = "getRole";
    public static string $EDIT_ROLE = "editRole";
    public static string $DELETE_ROLE = "deleteRole";
}
?>