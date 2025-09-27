<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel
{

    public function findUserById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = ?';
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    public function findUser($keyword)
    {
        $sql = 'SELECT * FROM users WHERE user_name LIKE ? OR user_email LIKE ?';
        $keywordPattern = '%' . $keyword . '%';
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('ss', $keywordPattern, $keywordPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
    public function auth($userName, $password)
    {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM users WHERE name = ? AND password = ?';
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('ss', $userName, $md5Password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $user;
    }

    /**
     * Delete user by id
     * @param $id
     * @return bool
     */
    public function deleteUserById($id)
    {
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Update user
     * @param $input
     * @return bool
     */
    public function updateUser($input)
    {
        $sql = 'UPDATE users SET name = ?, password = ? WHERE id = ?';
        $md5Password = md5($input['password']);
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('ssi', $input['name'], $md5Password, $input['id']);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Insert user
     * @param $input
     * @return bool
     */
    public function insertUser($input)
    {
        $sql = 'INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (?, ?)';
        $md5Password = md5($input['password']);
        $stmt = self::$_connection->prepare($sql);
        $stmt->bind_param('ss', $input['name'], $md5Password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = [])
    {
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE ?';
            $keywordPattern = '%' . $params['keyword'] . '%';
            $stmt = self::$_connection->prepare($sql);
            $stmt->bind_param('s', $keywordPattern);
            $stmt->execute();
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $sql = 'SELECT * FROM users';
            $stmt = self::$_connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $users = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
        return $users;
    }
}