var Config = {
	ajax:"http://vipintellect.ge/ge/ajax/index"
};

var app = new Vue({
	el:"#app",
	data:{
		table: [],
		table2: [],
		loaded:false
	},
	methods:{
		remove: function(id){
			this.table2 = this.table2.filter(function( obj ) {
				return obj.id !== id;
			});

			var ajaxFile = "/statuschagetasks";
		    $.ajax({
		        method: "POST",
		        url: Config.ajax + ajaxFile,
		        data: { 
		        	remove:true,
		        	id:id
		        }
		    }).done(function( msg ) {
		        var obj = $.parseJSON(msg);
		    });
			return true;
		},
		done: function(id){
			this.table = this.table.filter(function( obj ) {
				return obj.id !== id;
			});
			
	        var ajaxFile = "/statuschagetasks";
		    $.ajax({
		        method: "POST",
		        url: Config.ajax + ajaxFile,
		        data: { 
		        	id:id
		        }
		    }).done(function( msg ) {
		        var obj = $.parseJSON(msg);
		        location.reload();
		    });
			return true;
		},
		addnewtask: function(){
			var self = this;
			self.loaded = false;
	        var ajaxFile = "/addtasks";
	        var title = this.$refs.title.value;
	        var description = this.$refs.description.value;
	        var type = this.$refs.type.value;

	        $("#addtaskform").trigger("reset");
		    $.ajax({
		        method: "POST",
		        url: Config.ajax + ajaxFile,
		        data: { 
		        	title:title,
		        	description:description,
		        	type:type
		        }
		    }).done(function( msg ) {
		        var obj = $.parseJSON(msg);
		        self.table = obj.Success.Table;
		        self.loaded = true;
		    });
			return true;
		}
	},
	mounted: function () {
        var self = this;
        var ajaxFile = "/loadtasks";
	    $.ajax({
	        method: "POST",
	        url: Config.ajax + ajaxFile,
	        data: { 
	        	status:1
	        }
	    }).done(function( msg ) {
	        var obj = $.parseJSON(msg);
	        self.table = obj.Success.Table;
	        self.loaded = true;
	    });

	    $.ajax({
	        method: "POST",
	        url: Config.ajax + ajaxFile,
	        data: { 
	        	status:2
	        }
	    }).done(function( msg ) {
	        var obj = $.parseJSON(msg);
	        self.table2 = obj.Success.Table;
	        self.loaded = true;
	    });
    }
}) 