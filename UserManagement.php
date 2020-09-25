<?php include('server.php') ?>
<?php 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<html>
<head>
    <title>User Management System</title>
    <script src="jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
    <script src="jquery.dataTables.min.js"></script>
    <script src="angular-datatables.min.js"></script>
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="datatables.bootstrap.css">

</head>
<body ng-app="crudApp" ng-controller="crudController">
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
	<?php  if (isset($_SESSION['username'])&&$_SESSION['username']=="Administrator") : ?>

		<div class="container" ng-init="fetchData()">
			<br/>
			<h2 align="center">User Management System</h2>
			<br/>
			<div class="alert alert-success alert-dismissible" ng-show="success">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				{{successMessage}}
			</div>
			<br/>
			<div class="table-responsive" style="overflow-x: unset;">
				<table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>ID</th>
						<th>User Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Type</th>
						<th>Birthday</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="user in userData">
						<td>{{user.id}}</td>
						<td>{{user.username}}</td>
						<td>{{user.email}}</td>
						<td>{{user.tel}}</td>
						<td>{{user.Permission =='0'? 'User' : 'Admin'}}</td>
						<td>{{user.birthday}}</td>
						<td>
							<button type="button" ng-click="fetchSingleData(user.id)" class="btn btn-warning btn-xs">Edit</button>
						</td>
						<td>
							<button type="button" ng-click="deleteData(user.id)" class="btn btn-danger btn-xs">Delete</button>
						</td>
					</tr>
					</tbody>
				</table>
			</div>

		</div>
		</body>
		</html>

		<div class="modal fade" tabindex="-1" role="dialog" id="crudmodal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form method="post" ng-submit="submitForm()">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">{{modalTitle}}</h4>
						</div>
						<div class="modal-body">
							<div class="alert alert-danger alert-dismissible" ng-show="error">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								{{errorMessage}}
							</div>
							<div class="form-group">
								<label>Enter user name</label>
								<input type="text" name="username" ng-model="username" class="form-control"/>
							</div>
							<div class="form-group">
								<label>Enter email</label>
								<input type="text" name="email" ng-model="email" class="form-control"/>
							</div>
							<div class="form-group">
								<label>Enter tel </label>
								<input type="text" name="tel" ng-model="tel" class="form-control"/>
							</div>
							<div class="form-group">
								<label>Select type</label>
								<select name="Permission" ng-model="Permission" class="form-control"/>
									<option value="1">Admin</option>
									<option value="0">User</option>
								</select>
							</div>
							<div class="form-group">
								<label>Enter birthday</label>
								<input type="text" name="birthday" ng-model="birthday" class="form-control"/>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="hidden_id" value="{{hidden_id}}"/>
							<input type="submit" name="submit" id="submit" class="btn btn-info" value="{{submit_button}}"/>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php endif ?>


<script>

    var app = angular.module('crudApp', ['datatables']);
    app.controller('crudController', function ($scope, $http) {

        $scope.success = false;

        $scope.error = false;

        $scope.fetchData = function () {
            $http.get('fetch_data.php').success(function (data) {
                $scope.userData = data;
            });
        };

        $scope.openModal = function () {
            var modal_popup = angular.element('#crudmodal');
            modal_popup.modal('show');
        };

        $scope.closeModal = function () {
            var modal_popup = angular.element('#crudmodal');
            modal_popup.modal('hide');
        };

        $scope.addData = function () {
            $scope.modalTitle = 'Add Data';
            $scope.submit_button = 'Insert';
            $scope.openModal();
        };

        $scope.submitForm = function () {
            $http({
                method: "POST",
                url: "insert.php",
                data: {
                    'username': $scope.username,
                    'email': $scope.email,
                    'tel': $scope.tel,
                    'Permission': $scope.Permission,
                    'birthday': $scope.birthday,
                    'action': $scope.submit_button,
                    'id': $scope.hidden_id
                }
            }).success(function (data) {
                if (data.error != '') {
                    $scope.success = false;
                    $scope.error = true;
                    $scope.errorMessage = data.error;
                }
                else {
                    $scope.success = true;
                    $scope.error = false;
                    $scope.successMessage = data.message;
                    $scope.form_data = {};
                    $scope.closeModal();
                    $scope.fetchData();
                }
            });
        };

        $scope.fetchSingleData = function (id) {
            $http({
                method: "POST",
                url: "insert.php",
                data: {'id': id, 'action': 'fetch_single_data'}
            }).success(function (data) {
                $scope.username = data.username;
                $scope.email = data.email;
                $scope.tel = data.tel;
                $scope.Permission = data.Permission;
                $scope.birthday = data.birthday;
                $scope.hidden_id = id;
                $scope.modalTitle = 'Edit Data';
                $scope.submit_button = 'Edit';
                $scope.openModal();
            });
        };

        $scope.deleteData = function (id) {
            if (confirm("Are you sure you want to remove it?")) {
                $http({
                    method: "POST",
                    url: "insert.php",
                    data: {'id': id, 'action': 'Delete'}
                }).success(function (data) {
                    $scope.success = true;
                    $scope.error = false;
                    $scope.successMessage = data.message;
                    $scope.fetchData();
                });
            }
        };

    });

</script>