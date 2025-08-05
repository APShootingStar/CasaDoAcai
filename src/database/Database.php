<?php

// Constantes de conexão com o banco de dados
// Mantenha as suas configurações, se estiverem diferentes
define("MYSQL_SERVER", "localhost");
define("MYSQL_PORT", "3306");
define("MYSQL_DATABASE", "casa_do_acai");
define("MYSQL_CHARSET", "UTF8");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "");

class Database {
    
   private $connection;

   // Método privado para conectar ao banco de dados
   private function connect()
   {
       try {
           $dsn = 'mysql:host=' . MYSQL_SERVER . ';port=' . MYSQL_PORT . ';dbname=' . MYSQL_DATABASE . ';charset=' . MYSQL_CHARSET;
           $this->connection = new PDO($dsn, MYSQL_USER, MYSQL_PASS, [
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
               PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
               PDO::ATTR_PERSISTENT => true
           ]);
       } catch (PDOException $e) {
           die("Erro de conexão com o banco de dados: " . $e->getMessage());
       }
   }

   // Método privado para desconectar
   private function disconnect()
   {
       $this->connection = null;
   }
   public function statement($sql, $parameters = [])
   {
       $this->connect();
       try {
           $stmt = $this->connection->prepare($sql);
           $stmt->execute($parameters);
           return $stmt;
       } catch (PDOException $e) {
           // Lança a exceção para que o código que chamou este método possa tratá-la
           throw new PDOException("Erro na instrução SQL: " . $e->getMessage(), (int)$e->getCode());
       } finally {
           // Garante que a desconexão ocorra sempre, mesmo em caso de erro
           $this->disconnect();
       }
   }

   // Método para SELECT
   public function select($sql, $parameters = [])
   {
       if(!preg_match('/^SELECT/i', trim($sql))){
           throw new Exception("Erro: A instrução não é um SELECT.");
       }
       $stmt = $this->statement($sql, $parameters);
       return $stmt->fetchAll();
   }
   
   // Método para INSERT
   public function insert($sql, $parameters = [])
   {
       if(!preg_match('/^INSERT/i', trim($sql))){
           throw new Exception("Erro: A instrução não é um INSERT.");
       }
       $this->connect();
       try {
           $stmt = $this->connection->prepare($sql);
           $stmt->execute($parameters);
           $lastId = $this->connection->lastInsertId();
           return $lastId;
       } catch (PDOException $e) {
           throw new PDOException("Erro na instrução INSERT: " . $e->getMessage(), (int)$e->getCode());
       } finally {
           $this->disconnect();
       }
   }
   
   // Método para UPDATE
   public function update($sql, $parameters = [])
   {
       if(!preg_match('/^UPDATE/i', trim($sql))){
           throw new Exception("Erro: A instrução não é um UPDATE.");
       }
       $this->statement($sql, $parameters);
       return true;
   }

   // Método para DELETE
   public function delete($sql, $parameters = [])
   {
       if(!preg_match('/^DELETE/i', trim($sql))){
           throw new Exception("Erro: A instrução não é um DELETE.");
       }
       $this->statement($sql, $parameters);
       return true;
   }
}
