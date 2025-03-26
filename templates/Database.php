<?php
class Database
{
    private PDO $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function recupererTout(string $table, string $champs = "*"): array
    {
        $sql = "SELECT $champs FROM `$table`";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupererQuand(string $table, string $condition, array $params = [], string $champs = "*"): array
    {
        $sql = "SELECT $champs FROM `$table` WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupererId(string $table, $id, string $champs = "*", string $colonneId = "id"): ?array
    {
        $sql = "SELECT $champs FROM `$table` WHERE $colonneId = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function inserer(string $table, array $data): int
    {
        $champs = implode(', ', array_keys($data));
        $parametres = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO `$table` ($champs) VALUES ($parametres)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $colonne => $valeur) {
            $stmt->bindValue(':' . $colonne, $valeur);
        }

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $condition, array $paramsCondition = []): int
    {
        $champs = [];
        foreach ($data as $colonne => $valeur) {
            $champs[] = "$colonne = :update_$colonne";
        }
        $champsSQL = implode(', ', $champs);

        $sql = "UPDATE `$table` SET $champsSQL WHERE $condition";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $colonne => $valeur) {
            $stmt->bindValue(":update_$colonne", $valeur);
        }

        foreach ($paramsCondition as $placeholder => $valeur) {
            $stmt->bindValue($placeholder, $valeur);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }
}
