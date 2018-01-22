<?php

use Slim\Http\Request;
use Slim\Http\Response;

// DB
function getConnection(){
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="football";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

// Routes
/*
 * DOC
 */
$app->get('/', function($request, $response, $args) use ($app){
	$response->getBody()->write('
		<style type="text/css" media="screen">
			table, tr, td{ border: 1px solid #000;}
			tr.heading > td{ text-align: center; }
			ul { margin: 0}
			ul > li { list-style: none; }
		</style>

		<table>
			<caption>Table des routes</caption>
			<thead>
				<tr> <th>Routes</th> <th>Verboses</th> <th>Retour</th> <th>Description</th> </tr>
			</thead>
			<tbody>
				<tr class="heading"><td colspan="4">User</td></tr>
				<tr>
					<td>/users</td>
					<td>GET</td>
					<td><code>[{user: {<ul><li>id: int,</li><li>mail: string,</li><li>password: string,</li><li>last_name: string,</li><li>first_name: string,</li><li>pseudo: string,</li><li>nb_round: int,</li><li>is_mercato_closed: int,</li><li>money: int},</li></ul> {...}}]</code></td>
					<td>Retoune la liste de tous les utilisateurs</td>
				</tr>
				<tr>
					<td>/users</td>
					<td>POST</td>
					<td><code>{user: {<ul><li>mail: string,</li><li>password: string,</li><li>last_name: string,</li><li>first_name: string,</li><li>pseudo: string,</li></ul>}}</code></td>
					<td>Formulaire d\'inscription utilisateur</td>
				</tr>
				<tr>
					<td>/users/:id</td>
					<td>GET</td>
					<td><code>{user: {<ul><li>mail: string,</li><li>password: string,</li><li>last_name: string,</li><li>first_name: string,</li><li>pseudo: string,</li></ul>}}</code></td>
					<td>Formulaire d\'inscription utilisateur</td>
				</tr>
			</tbody>
		</table>
		');

	return $response;
});

/*
 * User
 */

$app->group('/users', function(){
	$this->get('', function($args){
		$sql = "SELECT * FROM user";
		try {
			$db = getConnection();
			$stmt = $db->query($sql);
			$user = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($user);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
	$this->post('', function($request, $response, $args){
		$user = json_decode($request->getBody());
		$sql_post = "INSERT INTO user (mail, password, last_name, first_name, pseudo, nb_round)
						VALUES (:mail, :password, :last_name, :first_name, :pseudo, :nb_round)";
		$sql_get = "SELECT * FROM user ORDER BY id DESC LIMIT 1";
		try {
			$db = getConnection();
			$stmt = $db->prepare($sql_post);
			$stmt->bindParam("mail", $user->mail);
			$stmt->bindParam("password", $user->password);
			$stmt->bindParam("last_name", $user->last_name);
			$stmt->bindParam("first_name", $user->first_name);
			$stmt->bindParam("pseudo", $user->pseudo);
			$user->id = $db->lastInsertId();
			$stmt->execute();

			$stmt2 = $db->query($sql_get);
			$user = $stmt2->fetchAll(PDO::FETCH_OBJ);
			$stmt2->execute();
			$db = null;
			echo json_encode($user);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
	$this->get('/{id}', function($request, $response, $args){
		$sql = "SELECT * FROM user WHERE id =". $args["id"];
		try {
			$db = getConnection();
			$stmt = $db->query($sql);
			$user = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($user);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
	$this->put('/{id}', function($request, $response, $args){
		$user = json_decode($request->getBody());
		$sql_put = "UPDATE user	SET pseudo = :pseudo, password = :password WHERE id = :id";
		$sql_get = "SELECT * FROM user WHERE id = ". $args['id'];
		try {
			$db = getConnection();
			$stmt1 = $db->prepare($sql_put);
			$stmt1->bindParam("pseudo", $user->pseudo);
			$stmt1->bindParam("password", $user->password);
			$stmt1->bindParam("id", $args['id']);
			$stmt1->execute();

			$stmt2 = $db->query($sql_get);
			$user = $stmt2->fetchAll(PDO::FETCH_OBJ);
			$stmt2->execute();
			$db = null;
			echo json_encode($user);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
	$this->get('/{id}/players', function($request, $response, $args){
		$sql = "SELECT * FROM player WHERE user_id =". $args["id"];
		try {
			$db = getConnection();
			$stmt = $db->query($sql);
			$user = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($user);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
    $this->get('/{id}/bids', function($request, $response, $args){
        $sql = "SELECT * FROM player WHERE user_id =". $args["id"];
        try {
            $db = getConnection();
            $stmt = $db->query($sql);
            $user = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo json_encode($user);
        } catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    });
});

/*
 * Authentication
 */
$app->post('/login', function($request, $response, $args){
	$user = json_decode($request->getBody());
	$sql = "SELECT * FROM user WHERE mail = :mail AND password = :password";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("mail", $user->mail);
		$stmt->bindParam("password", $user->password);
		// $stmt->execute(array('mail' => $user->mail, 'password' => $user->password));

		$stmt->execute();
		$user = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e){
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
});

/* 
 * Players
 */
$app->group('/players', function(){
	$this->get('', function(){
		$sql = "SELECT * FROM player";
		try {
			$db = getConnection();
			$stmt = $db->query($sql);
			$player = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($player);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
    $this->get('/free', function(){
        $sql = "SELECT * FROM player WHERE user_id IS NULL";
        try {
            $db = getConnection();
            $stmt = $db->query($sql);
            $player = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo json_encode($player);
        } catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    });
	$this->get('/{id}', function($request, $response, $args){
		$sql = "SELECT * FROM player WHERE id = ". $args['id'];
		try {
			$db = getConnection();
			$stmt = $db->query($sql);
			$player = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($player);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
	$this->put('/{id}', function($request, $response, $args){
		$player = json_decode($request->getBody());
		$sql_put = "UPDATE player	SET user_id = :user_id WHERE id = :id";
		$sql_get = "SELECT * FROM player WHERE id = ". $args['id'];
		try {
			$db = getConnection();
			$stmt1 = $db->prepare($sql_put);
			$stmt1->bindParam("user_id", $player->user_id);
			$stmt1->bindParam("id", $args['id']);
			$stmt1->execute();

			$stmt2 = $db->query($sql_get);
			$player = $stmt2->fetchAll(PDO::FETCH_OBJ);
			$stmt2->execute();
			$db = null;
			echo json_encode($player);
		} catch(PDOException $e){
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	});
});

/*
 * Bid
 */

$app->group('/bids', function(){
    $this->post('', function($request, $response, $args){
        $bid = json_decode($request->getBody());
        $sql_post = "INSERT INTO bid (id_player, id_user, price, nb_round)
						VALUES (:id_player, :id_user, :price, :nb_round)";
        $sql_get = "SELECT * FROM bid ORDER BY id DESC LIMIT 1";
        try {
            $db = getConnection();
            $stmt = $db->prepare($sql_post);
            $stmt->bindParam("id_player", $bid->id_player);
            $stmt->bindParam("id_user", $bid->id_user);
            $stmt->bindParam("price", $bid->price);
            $stmt->bindParam("nb_round", $bid->nb_round);
            $bid->id = $db->lastInsertId();
            $stmt->execute();

            $stmt2 = $db->query($sql_get);
            $bid = $stmt2->fetchAll(PDO::FETCH_OBJ);
            $stmt2->execute();
            $db = null;
            echo json_encode($bid);
        } catch(PDOException $e){
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    });
});