var demo = new Vue({
  el: '#demo',  
  created: function(){
		this.GetGrafica();		
	},
  data: {
    message: 'Hello Vue.js!'
  },
  methods: {
  	GetGrafica:function(){
  		this.GetGraficaTrabajadores();
  		this.GetTotalUsuariosPorTipos();
  		this.GetUsuariosPorUnidadesOrganizativas();
  	},
  	GetGraficaTrabajadores:function(){

  			//grafica de linia
  			var url='graficaTrabajadores';
  			axios.get(url).then(response=>{
  				new Chartist.Line('#chart-line', response.data, {
		          low: 0,
		          showArea: true
		        });

  			});  
  	},
  	GetTotalUsuariosPorTipos:function(){
  		//grafica de pastel
  			var url='getTotalUsuariosPorTipos';
  			
  			axios.get(url).then(response=>{  				
	  				var data = response.data;
	  				var sum = function(a, b) { return a + b };
					var options = {
					  labelInterpolationFnc: function(value) {
					    return value
					  }
					};

					var responsiveOptions = [
					  ['screen and (min-width: 640px)', {
					    chartPadding: 50,
					    labelOffset: 10,
					    labelDirection: 'explode',					    
					    labelInterpolationFnc: function(value) {
					      return value;			      
					    }
					  }],
					  ['screen and (min-width: 1024px)', {
					    labelOffset: 40,
					    chartPadding: 5					    
					  }]
					];

					new Chartist.Pie('#chart-pastel', data, options, responsiveOptions);

	  		});			

  	},
  	GetUsuariosPorUnidadesOrganizativas:function(){
  		var url='getusuariosPorUnidadesOrganizativas';  			
  		axios.get(url).then(response=>{
  			var datos = response.data;

  			var responsiveOptions = [
			  ['screen and (min-width: 640px)', {
			    chartPadding: 50,
			    labelOffset: 10,
			    labelDirection: 'explode',					    
			    labelInterpolationFnc: function(value) {
			      return value;			      
			    }
			  }],
			  ['screen and (min-width: 1024px)', {
			    labelOffset: 40,
			    chartPadding: 5					    
			  }]
			];

  			var chart = new Chartist.Pie('#chart-roska', datos, {
			  donut: true,
			  showLabel: true
			},responsiveOptions);

			chart.on('draw', function(data) {
			  if(data.type === 'slice') {
			    // Get the total path length in order to use for dash array animation
			    var pathLength = data.element._node.getTotalLength();

			    // Set a dasharray that matches the path length as prerequisite to animate dashoffset
			    data.element.attr({
			      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			    });

			    // Create animation definition while also assigning an ID to the animation for later sync usage
			    var animationDefinition = {
			      'stroke-dashoffset': {
			        id: 'anim' + data.index,
			        dur: 1000,
			        from: -pathLength + 'px',
			        to:  '0px',
			        easing: Chartist.Svg.Easing.easeOutQuint,
			        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
			        fill: 'freeze'
			      }
			    };

			    // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
			    if(data.index !== 0) {
			      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			    }

			    // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
			    data.element.attr({
			      'stroke-dashoffset': -pathLength + 'px'
			    });

			    // We can't use guided mode as the animations need to rely on setting begin manually
			    // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
			    data.element.animate(animationDefinition, false);
			  }
			});

			// For the sake of the example we update the chart every time it's created with a delay of 8 seconds
			chart.on('created', function() {
			  if(window.__anim21278907124) {
			    clearTimeout(window.__anim21278907124);
			    window.__anim21278907124 = null;
			  }
			  window.__anim21278907124 = setTimeout(chart.update.bind(chart), 10000);
			});
  		});
	  		
		}//GetusuariosPorUnidadesOrganizativas
	
	},//methods
})//new Vue
