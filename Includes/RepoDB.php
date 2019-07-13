<?php

namespace SQLData;
/**
 * RepoDB short summary.
 *
 * RepoDB description.
 *
 * @version 1.0
 * @author rickj
 */
class RepoDB
{
    //PERSONNEL
    public function get_locations($pdo) {
        $stmt =$pdo->query('SELECT lo.location_id, '
                               .'  lo.location '
                           .' FROM location lo '
                           .'ORDER BY lo.location');
        $locations = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $locations[] = [
                'Location_Id' => $row['location_id'],
                   'Location' => $row['location'],
                      ];
        }
        return $locations;
    }

    public function get_personnel($pdo) {
        $stmt =$pdo->query('SELECT l.login_id, '
                               .'  l.first_name, '
                               .'  l.last_name, '
                               .'  w.status,'
                               .'  w.location_id,'
                               .'  lo.location,'
                               .'  COALESCE(w.location_other,\'\') as location_other,'
                               .'  w.return_time,'
                               .'  COALESCE(w.details,\'\') as details'
                          .' FROM login l'
                         .' INNER JOIN workstatus w '
                         .'    ON w.login_id = l.login_id '
                         .' INNER JOIN location lo '
                         .'    ON lo.location_id = w.location_id '
                         .' ORDER BY l.last_name, l.first_name  '
                         );

        $personnel = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $personnel[] = [
                'Login_id' => $row['login_id'],
              'First_Name' => $row['first_name'],
               'Last_Name' => $row['last_name'],
                  'Status' => $row['status'],
             'Location_Id' => $row['location_id'],
                'Location' => $row['location'],
          'Location_Other' => $row['location_other'],
             'Return_Time' => $row['return_time'],
                 'Details' => $row['details'],
                      ];
        }
        return $personnel;
    }

    public function update_person($pdo, $person) {
        try{
            $stmt =$pdo->prepare('UPDATE workstatus '
                                   . ' SET status = :status, '
                                   . ' location_id = :location_id, '
                                   . ' location_other = :other, '
                                   . ' return_time = :_return, '
                                   . ' details = :details '
                                   . ' WHERE login_id = :login_id;'
                                );
            $stmt->bindValue(':login_id', $person->login_id);
            $stmt->bindValue(':status', $person->status);
            $stmt->bindValue(':location_id', $person->location_id);
            $stmt->bindValue(':other', $person->other);
            $stmt->bindValue(':_return', $person->_return);
            $stmt->bindValue(':details', $person->details);
            $stmt->execute();

        }
        catch (\PDOException $e) {
            header('Location: ../Error.php?errormessage='.urlencode($e->getMessage()));
            die();
        }

        return $stmt->rowCount();
    }

    //public function update_person($pdo, $person) {
    //    // sql statement
    //    //$sql = 'UPDATE workstatus '
    //    //    . ' SET status = :status, '
    //    //    . ' location_id = :location_id, '
    //    //    . ' location_other = :other, '
    //    //    . ' return_time = :_return, '
    //    //    . ' details = :details '
    //    //    . ' WHERE login_id = :login_id';

    //    //$stmt = $pdo->prepare($sql);

    //    //// bind values to the statement
    //    //$stmt->bindValue(':login_id', $person->login_id);
    //    //$stmt->bindValue(':status', $person->status);
    //    //$stmt->bindValue(':location_id', $person->location_id);
    //    //$stmt->bindValue(':other', $person->other);
    //    //$stmt->bindValue(':_return', $person->_return);
    //    //$stmt->bindValue(':details', $person->details);
    //    //// update data in the database
    //    //$stmt->execute();

    //    //$pdo->beginTransaction();

    //    //$login_id = GetInt($person->login_id);
    //    //$login_id = (int)$login_id;

    //    $stmt = $pdo->prepare("DELETE FROM workstatus WHERE login_id = 1;");
    //    //$stmt->bindValue(':login_id', $person->login_id);
    //    $stmt->execute();
    //    //$pdo->commit();



    //    // return the number of row affected
    //    return $stmt->rowCount();

    //}



}


    //public function update_person($pdo, $person) {
    //    try{
    //        $stmt =$pdo->prepare('UPDATE workstatus '
    //                               . ' SET status = :status, '
    //                               . ' location_id = :location_id, '
    //                               . ' location_other = :other, '
    //                               . ' return_time = :_return, '
    //                               . ' details = :details '
    //                               . ' WHERE login_id = :login_id'
    //                            );
    //        $stmt->bindValue(':login_id', $person->login_id);
    //        $stmt->bindValue(':status', $person->status);
    //        $stmt->bindValue(':location_id', $person->location_id);
    //        $stmt->bindValue(':other', $person->other);
    //        $stmt->bindValue(':_return', $person->_return);
    //        $stmt->bindValue(':details', $person->details);
    //        $stmt->execute();

    //    }
    //    catch (\PDOException $e) {
    //        header('Location: ../Error.php?errormessage='.urlencode($e->getMessage()));
    //        die();

    //    }

    //    return $stmt->rowCount();
    //}