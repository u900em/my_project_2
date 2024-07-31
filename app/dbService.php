<?php

    namespace App;
    use Aura\SqlQuery\QueryFactory;
    use PDO;

    class dbService {
        private $queryFactory, $pdo;

        public function __construct(QueryFactory $queryFactory, PDO $pdo) {
            $this->queryFactory = $queryFactory;
            $this->pdo = $pdo;
        }

        public function insert($table, $data) {
            $insert = $this->queryFactory->newInsert();
            $insert->into($table)->cols($data);
            $sth = $this->pdo->prepare($insert->getStatement());
            $sth->execute($insert->getBindValues());
        }

        public function getAll($table, $col = '*') {
            $select = $this->queryFactory->newSelect();
            $select->cols([$col])->from($table);
            $sth = $this->pdo->prepare($select->getStatement());
            $sth->execute($select->getBindValues());
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getOne($table, $id) {
            $select = $this->queryFactory->newSelect();
            $select->cols(['*'])->from($table)->where('id = :id', ['id' => $id]);
            $sth = $this->pdo->prepare($select->getStatement());
            $sth->execute($select->getBindValues());
            return $sth->fetch(PDO::FETCH_ASSOC);
        }

        public function update($table, $data, $id = '') {
            $update = $this->queryFactory->newUpdate();
            $update->table($table)->cols($data)->where('id = :id', ['id' => $id]);
            $sth = $this->pdo->prepare($update->getStatement());
            $sth->execute($update->getBindValues());
        }

        public function delete($table, $id) {
            $delete = $this->queryFactory->newDelete();
            $delete->from($table)->where('id = :id', ['id' => $id]);
            $sth = $this->pdo->prepare($delete->getStatement());
            $sth->execute($delete->getBindValues());
        }
    }
?>