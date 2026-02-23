<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Vue js task manager</title>
  <link rel="stylesheet" type="text/css" href="/public/css/vue/app.css" />
</head>
<body>
<div id="app">
	<div v-show="!loaded">მიმდინარეობს ჩატვირთვა...</div>
	<div v-show="loaded">
		<p><a href="/<?=$_SESSION["LANG"]?>/dashboard/index"><-ადმინისტრირების პანელში დაბრუნება</a></p>
		<h2>დასრულებული</h2>
		<table cellspacing="5" cellpadding="5" width="100%" border="1">
			<tr>
				<th>ს.კ.</th>
				<th>სათაური</th>
				<th>აღწერა</th>
				<th>ტიპი</th>
				<th>მოქმედება</th>
			</tr>
			<tr v-for="tab in table2">
				<td>{{ tab.id }}</td>
				<td>{{ tab.title }}</td>
				<td>{{ tab.description }}</td>
				<td>{{ tab.type }}</td>
				<td>
					<button v-on:click="remove(tab.id)">წაშლა</button>
				</td>
			</tr>
		</table>

		<h2>გასაკეთებელი დავალებები</h2>
		<table cellspacing="5" cellpadding="5" width="100%" border="1">
			<tr>
				<th>ს.კ.</th>
				<th>სათაური</th>
				<th>აღწერა</th>
				<th>ტიპი</th>
				<th>მოქმედება</th>
			</tr>
			<tr v-for="tab in table">
				<td>{{ tab.id }}</td>
				<td>{{ tab.title }}</td>
				<td>{{ tab.description }}</td>
				<td>{{ tab.type }}</td>
				<td>
					<button v-on:click="done(tab.id)">დასრულდა</button>
				</td>
			</tr>
		</table>

		<h3>დავალების დამატება</h3>
		<form action="javascript:void(0)" method="post" id="addtaskform">
			<label>სათაური</label><br>
			<input type="text" ref="title" /><br>
			<label>აღწერა</label><br>
			<textarea ref="description"></textarea><br>
			<label>ტიპი</label><br>
			<select ref="type">
				<option value="დაბალი">დაბალი</option>
				<option value="საშუალო">საშუალო</option>
				<option value="სასწრაფო">სასწრაფო</option>			
			</select><br>
			<button v-on:click="addnewtask()">დამატება</button>
		</form>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script type="text/javascript" src="/public/js/vue/app.js"></script>
</body>
</html>