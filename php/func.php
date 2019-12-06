<?php
require 'connect.php';

function add_salon ($POST, $FILES, $db_name)
{
    $object = salon_array($POST);
    $connect = connect();

    $object["file_path"] = add_file($FILES, $db_name, 0);
    if ($object["file_path"] == -1)
        return -1;

    $row = 'NULL,';
    foreach ($object as $name => $value)
        $row .= ":$name,";
    $row = substr($row, 0, -1);

    $query = "INSERT INTO $db_name VALUES ($row)";
    if (!$connect->prepare($query)->execute($object))
        return -1;

    return 1;
}

function add_car ($POST, $FILES, $db_name)
{
    $connect = connect();

    $object = car_array($POST);
    $id_salon = htmlspecialchars($POST["id_salon"]);
    $object["file_path"] = add_file($FILES, "car", 0);
    if ($object["file_path"] == -1)
        return -1;

    $row = 'NULL,';
    foreach ($object as $name => $value)
        $row .= ":$name,";
    $row = substr($row, 0, -1);

    $query = "INSERT INTO $db_name VALUES ($row)";
    $rez1 = $connect->prepare($query)->execute($object);
    $id_car = $connect->lastInsertId();

    $query = "INSERT INTO relation (id_salon, id_car) VALUES ($id_salon   , $id_car)";
    $rez2 = $connect->prepare($query)->execute();
    $connect = null;
    if (!$rez1 or !$rez2)
        return -1;
    return 1;
}

function add_file ($FILES, $db_name, $id)
{
    define("upload_dir",'user_file/');

    if ($FILES["error"] !== UPLOAD_ERR_OK)
        return -1;

    $file_type = exif_imagetype($FILES["tmp_name"]);
    $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
    if (!in_array($file_type, $allowed))
        return -1;

    if ($id != 0)
        delete_file($db_name, $id);

    $FILES["name"] = preg_replace("/[^A-Z0-9._()-]/i", '_', $FILES["name"]);

    $i = 0;
    $parts = pathinfo($FILES["name"]);
    while (file_exists(upload_dir.$FILES["name"]))
    {
        $i++;
        $FILES["name"] = $parts["filename"]. "_" . "(" . $i . ")".  "." . $parts["extension"];
    }

    $upload_file = upload_dir.basename($FILES['name']);
    if (!move_uploaded_file($FILES["tmp_name"], $upload_file))
        return -1;
    chmod($upload_file, 0644);
    return $upload_file;
}

function delete_file($db_name, $id)
{
    $connect = connect();
    $query = "SELECT * FROM $db_name WHERE id_$db_name=$id";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row["file_path"] != "0")
        unlink($row["file_path"]);
}

function save_salon($object, $FILES, $db_name, $id)
{
    if ($FILES["name"] == "") {
        delete_file($db_name, $id);
        $object["file_path"] = 0;
    }
    else {
        $object["file_path"] = add_file($FILES, $db_name, $id);
        if ($object["file_path"] == -1)
            return -1;
    }

    $row = '';
    foreach ($object as $key => $value)
        $row .= "$key=:$key, ";
    $row = substr($row, 0, -2);

    $connect = connect();
    $query = "UPDATE $db_name SET $row WHERE id_$db_name=$id";
    $rez = $connect->prepare($query)->execute($object);
    $connect = null;
    if (!$rez)
        return -1;
    return 1;
}


function save_car($POST, $GET, $FILES, $db_name)
{
    $object = car_array($POST);
    $id_car = htmlspecialchars($GET["id_car"]);
    $id_salon = htmlspecialchars($POST["id_salon"]);
    if ($FILES["name"] == "") {
        delete_file($db_name, htmlspecialchars($_GET["id_car"]));
        $object["file_path"] = 0;
    }
    else {
        $object["file_path"] = add_file($FILES, $db_name, $id_car);
        if ($object["file_path"] == -1)
            return -1;
    }

    $row = '';
    foreach ($object as $key => $value)
        $row .= "$key=:$key, ";
    $row = substr($row, 0, -2);
    //var_dump($row);
    $connect = connect();
    $query = "UPDATE $db_name SET $row WHERE id_$db_name=$id_car";
    //var_dump($query);
    $connect->prepare($query)->execute($object);

    $query = "UPDATE relation SET id_salon=$id_salon WHERE id_car=$id_car";
    //var_dump($query);
    $rez2 = $connect->prepare($query)->execute();
    $connect = null;
    if (!$rez2)
        return -1;

    return 1;
}

function delete_car($id)
{
    $id = htmlspecialchars($id);
    $connect = connect();

    delete_file("car", $id);

    $query = "DELETE FROM car WHERE id_car=$id";
    $rez1 = $connect->prepare($query)->execute();

    $query = "DELETE FROM relation WHERE id_car=$id";
    $rez2 = $connect->prepare($query)->execute();

    $connect = null;
    if (!$rez1 or !$rez2)
        return -1;
    return 1;
}

function delete_salon($id)
{
    $id = htmlspecialchars($id);
    $connect = connect();

    $query = "SELECT * FROM relation WHERE id_salon=$id";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $check = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$check)
    {
        delete_file("salon", $id);
        $query = "DELETE FROM salon WHERE id_salon=$id";
        $connect->prepare($query)->execute();
        $connect = null;
        return 1;
    }

    if ($check)
    {
        $connect = null;
        return -1;
    }

    $connect = null;
    return -1;
}

function table_for_all ($db_name)
{
    $data = array();
    $connect = connect ();
    foreach ($connect->query("SELECT * FROM $db_name") as $row)
        $data[$row["id_$db_name"]] = $row;
    $connect = null;
    return $data;
}

function table_for_cars ($id_salon)
{
    $connect = connect();
    $query = "SELECT * FROM relation WHERE id_salon=$id_salon";
    foreach ($connect->query($query) as $row)
    {
        $query2 = "SELECT * FROM car WHERE id_car=$row[2]";
        foreach ($connect->query($query2) as $row2)
            $data[$row2[0]] = $row2;
    }
    $connect = null;
    if (isset($data))
        return $data;
}

function row($db_name, $id)
{
    $connect = connect();
    $query = "SELECT * FROM $db_name WHERE id_$db_name=$id";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
    $connect = null;
    return $edit;
}

function salon_array($POST)
{
    $salon = array();
    $salon['mark']       = htmlspecialchars($POST['mark']);
    $salon['number']     = htmlspecialchars($POST['tel']);
    $salon['email']      = htmlspecialchars($POST['email']);
    $salon['file_path']  = "";
    return $salon;
}

function car_array($POST)
{
    $car = array();
    $car['mark']            = htmlspecialchars($POST['mark']);
    $car['model']           = htmlspecialchars($POST['model']);
    $car['production_year'] = htmlspecialchars($POST['year']);
    $car['cost']            = htmlspecialchars($POST['cost']);
    $car['mileage']         = htmlspecialchars($POST['mileage']);
    $car['file_path']       = "";
    return $car;
}

function id_salon($mark)
{
    $connect = connect();
    $query = "SELECT * FROM salon WHERE mark = '$mark'";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
    $connect = null;
    return $edit["id_salon"];
}

function edit_check ($id, $db_name)
{
    $connect = connect ();
    foreach ($connect->query("SELECT * FROM $db_name") as $row)
        if ($row["id_$db_name"] == $id)
            return 1;
    return -1;
}

function edit_car_php ($POST, $GET, $FILES, $mark, $id_car)
{
    $id_salon = htmlspecialchars($GET['id_salon']);
    $car = car_array($POST, $mark);
    if ($FILES["name"] != "")
        $save = save_car($car, $FILES, "car", $id_car);
    else {
        $save = save_car($car, 0, "car", $id_car);
    }

    if (isset($GET['id_salon']) and ($save == 1))
        header ("Location: index_car.php?mark=$mark&id_salon=$id_salon&edit=true");
    if (isset($GET['id_salon']) and ($save == -1))
        header ("Location: index_car.php?mark=$mark&id_salon=$id_salon&edit=false");
    if (!isset($GET['id_salon']) and $save == -1)
        header ("Location: all_cars.php?id_car=$id_car&edit=false");
    if (!isset($GET['id_salon']) and $save == 1)
        header ("Location: all_cars.php?id_car=$id_car&edit=true");
}

function index_car_php($POST, $GET, $FILES)
{
    $mark = htmlspecialchars($GET['mark']);
    $car = car_array($POST, $mark);
    $rez = add_car($car, $FILES, 'car');
    if ($rez == 1)
        header("Location: index_car.php?mark=$mark&id_salon=$id_salon");
    else if ($rez == -1)
        header("Location: index_car.php?mark=$mark&id_salon=$id_salon&add=false");
    else
        header('Location: index_salon.php');
}

