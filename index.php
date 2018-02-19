<?php
/**
 * @SWG\Info(title="My First API", version="0.1")
 */
header('Access-Control-Allow-Origin: *');
require_once 'limonade.php';
/**
 * @SWG\Get(
 *     path="/{marka}/{model}/{pojemnosc}",
 *     @SWG\Response(response="200", description="Wyswietlanie samochodów")
 * )
 */
dispatch('/:marka/:model/:pojemnosc', 'my_get_function');
# same as dispatch_get('my_get_function');
        
    function my_get_function(){
        
        header('Content-Type: application/json');
        $nameMarka = params('marka');
        $nameModel = params('model');
        $namePojemnosc = params('pojemnosc');
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
       
        
        if(params('marka')!=null&&params('model')==null&&params('pojemnosc')==null){

            $query = new MongoDB\Driver\Query(array('marka'=>"$nameMarka"));
            $cursor = $manager->executeQuery('Samochody.samochody',$query);
        }
        else if(params('model')!=null&&params('pojemnosc')==null){
            $query = new MongoDB\Driver\Query(array('marka'=>"$nameMarka",'model'=>"$nameModel"));
            $cursor = $manager->executeQuery('Samochody.samochody',$query);
        }
        else if(params('pojemnosc')!=null){
            $query = new MongoDB\Driver\Query(array('marka'=>"$nameMarka",'model'=>"$nameModel",'pojemnosc'=>"$namePojemnosc"));
            $cursor = $manager->executeQuery('Samochody.samochody',$query);
        }
        else{
            $query = new MongoDB\Driver\Query([]);
            $cursor = $manager->executeQuery('Samochody.samochody',$query);
            echo json_encode($cursor->toArray());
        }
        if(params('marka')!=null){
            echo json_encode($cursor->toArray());
        }
    }
/**
 * @SWG\Post(
 *     path="/",
 *     @SWG\Response(response="200", description="dodawanie samochodów")
 * )
 */ 
   dispatch_post('/', 'my_post_function');
    function my_post_function()
    {
        /*
        $admin='admin';
        $pass='pass';
        
        
        if(($_SERVER["PHP_AUTH_USER"] != $admin) || ($_SERVER['PHP_AUTH_PW'] != $pass)){ 
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }*/
        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
        $bulk = new MongoDB\Driver\BulkWrite;      
        $string = file_get_contents('php://input','r');     
        $tmp=json_decode($string, true);  
        echo print_r($tmp);
        $bulk->insert(['marka'=>$tmp['marka'],'model'=>$tmp['model'],'pojemnosc'=>$tmp['pojemnosc']]);
        $result = $manager->executeBulkWrite('Samochody.samochody', $bulk);
    }
/**
 * @SWG\Put(
 *     path="/{marka}",
 *     @SWG\Response(response="200", description="Update samochodów")
 * )
 */
dispatch_put('/:marka', 'my_update_function');
    function my_update_function()
    {
        /*
        $admin='admin';
        $pass='pass';
        
        
        if(($_SERVER['PHP_AUTH_USER'] != $admin) || ($_SERVER['PHP_AUTH_PW'] != $pass)){ 
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        */
        $nameMarka = params('marka');
        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
 
        $string=file_get_contents("php://input");
 
        $bulk = new MongoDB\Driver\BulkWrite;
 
        $bulk->update(['marka'=>$nameMarka],['$set' => ['marka' => $string]],['multi' => true]);
 
        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
        $result = $manager->executeBulkWrite('Samochody.samochody', $bulk);
   
    }
/**
 * @SWG\Delete(
 *     path="/{marka}/{model}/{pojemnosc}",
 *     @SWG\Response(response="200", description="Usuwanie samochodów")
 * )
 */
dispatch_delete('/:marka/:model/:pojemnosc', 'my_delete_function');
    function my_delete_function()
    {
        /*
        $admin='admin';
        $pass='pass';
        
        
        if(($_SERVER['PHP_AUTH_USER'] != $admin) || ($_SERVER['PHP_AUTH_PW'] != $pass)){ 
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        */
        $nameMarka = params('marka');
        $nameModel = params('model');
        $namePojemnosc = params('pojemnosc');
        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
        $bulk = new MongoDB\Driver\BulkWrite();
 
        if(params('marka')!=null&&params('model')==null&&params('pojemnosc')==null){
            $bulk->delete(['marka' => $nameMarka]);
            $result = $manager->executeBulkWrite('Samochody.samochody', $bulk);
 
            echo "Usunięto $nameMarka \n";
 
        }else if(params('model')!=null&&params('pojemnosc')==null){
            $bulk->delete(['marka' => $nameMarka, 'model' => $nameModel]);
            $result = $manager->executeBulkWrite('Samochody.samochody', $bulk);
 
            echo "Usunięto $nameMarka $nameModel \n";
 
        }else if(params('pojemnosc')!=null){
            $bulk->delete(['model' => $nameModel , 'pojemnosc' => $namePojemnosc]);
            $result = $manager->executeBulkWrite('Samochody.samochody', $bulk);
 
            echo "Usunięto $nameMarka $nameModel $namePojemnosc\n";
        }
       
       
   
 
    }
 
function not_found($errno, $errstr, $errfile=null, $errline=null)
{
    set('errno', $errno);
    set('errstr', $errstr);
    set('errfile', $errfile);
    set('errline', $errline);
    return html("show_not_found_errors.html.php");
}
 
run();
?>

