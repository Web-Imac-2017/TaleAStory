'use strict';

import LIBS from './libs.js';
import config from '../config.js';

let GL;
let webGL={
	bg_anim: null,
	sound_visual: null,
	music : null,
	analyser : null,
	Particle: function(x,y,size){
		this.x=x;
		this.y=y;
		this.direction=[Math.random(),Math.random()];
		this.size=size;
		this.anim=0;
		this.opacity = Math.random()/3;
		this.update=function(){
			this.x+=this.direction[0]/300.;
			this.y+=this.direction[1]/300.;
			this.anim++;
		}
		
	},
	
	Bird : function(x,y,size){
		this.x=x;
		this.y=y;
		this.direction=[(Math.random()+0.2)*(-Math.sign(x)),Math.random()+0.3];
		this.size=size;
		this.anim=0;
		this.opacity = Math.random()*.60;
		this.update=function(){
			this.x+=this.direction[0]*0.75;
			this.y+=this.direction[1]*0.75;
			this.anim++;
		}
	},
	
	
	 Background: function(r,v,b,a){
		this.activecol=[r,v,b,a];

		this.animation=[0,0,0];
		this.transition=0;
		
		this.particlesLight=[];
		this.particlesBack=[];
		this.bird=null;
		
		this.addActiveCol = function (r,v,b,a) {
			this.activecol=[r,v,b,a];
		};
		

		this.move = function(x,y,z){
			this.animation=[x,y,z];
			if(this.transition==0)
				this.transition=1;
		};
		
		this.update = function(){
			if(this.transition <100 && this.transition!=0)
				this.transition++;
			
			if(Math.random()>0.94 && this.particlesLight.length<10){
				this.particlesLight.push(new webGL.Particle((Math.random()-0.5)*8,(Math.random()-0.5)*3,Math.pow(Math.exp(Math.random())-1,2)));
			}
			for (var i = 0; i < this.particlesLight.length; i++) {
				if(this.particlesLight[i].anim>400)
					this.particlesLight.shift();
				else{
					this.particlesLight[i].update();
				}
			}
			
			if(Math.random()>0.98 && this.particlesBack.length<10){
				this.particlesBack.push(new webGL.Particle(5.2*parseInt(Math.sign((Math.random()-0.5)))-0.2,(Math.random()-0.5),Math.random()+1));
			}
			if(Math.random()>0.97 && this.particlesBack.length<10){
				this.particlesBack.push(new webGL.Particle(11*(Math.random()-0.5),2.5*parseInt(Math.sign((Math.random()-0.5)))-0.1,Math.random()+1));
			}
			for (var i = 0; i < this.particlesBack.length; i++) {
				if(this.particlesBack[i].anim>400)
					this.particlesBack.shift();
				else{
					this.particlesBack[i].update();
				}
			}
		};
		
		this.animate= function(m){
			if(this.transition!=0){
				// LIBS.translate(m,[this.animation[0]*this.transition*this.transition/60. ,this.animation[1]*this.transition*this.transition/100.,0]);
				// LIBS.scale(m,[1+this.transition*this.transition/500.,1+this.transition*this.transition/500.,1]);
				
			}
		};
	},

	Background_Animation: function(r,v,b,a){
		var bg = new webGL.Background(r,v,b,a);
		var color = webGL.randColor();
		var bg2 = new webGL.Background(color[0],color[1],color[2],1);
		var landscape = Math.floor(Math.random()*7);
		var activate_landscape = false;
		var sounds=[];
		var transitions=[];
		var mute = true;

		
		this.getTransition= function(){
			return bg.transition;
		};
		
		this.setLandscape= function(x){
			this.activate_landscape=x;
		}
		
		this.getLandscape= function(){
			return this.activate_landscape;
		}
		
		this.getLandscapeNb= function(){
			return this.landscape;
		}
		
		this.move=function(x,y,z){
			
			if(bg.transition==0){
				bg.transition=1;
				bg.animation=[x,y,z];
				if(!mute)
					transitions[0].play();
			}
			
		};
		
		
		this.load = function(){
			this.landscape = Math.floor(Math.random()*7);
			this.activate_landscape = false;
			sounds = [new Audio(config.soundPath('wind_chime1.mp3')),new Audio(config.soundPath('wind_chime2.mp3')),new Audio(config.soundPath('wind_chime3.mp3')),new Audio(config.soundPath('wind_chime4.mp3')),new Audio(config.soundPath('wind_chime5.mp3')),new Audio(config.soundPath('wind_chime6.mp3')),new Audio(config.soundPath('chirp_1.mp3')),new Audio(config.soundPath('chirp_2.mp3')),new Audio(config.soundPath('chirp_3.mp3')),new Audio(config.soundPath('chirp_4.mp3'))];
			transitions=[new Audio(config.soundPath('transition.wav')),new Audio(config.soundPath('take_off_1.mp3')),new Audio(config.soundPath('take_off_2.mp3')),new Audio(config.soundPath('take_off_3.mp3'))];
			webGL.music=new Audio(config.soundPath('nature_1.mp3'));
			webGL.music.loop=true;
			webGL.music.play();
			mute = true;
		}
		
		this.mute = function(){
			if(mute == false){
				mute=true;
				webGL.music.pause();
				webGL.music.currentTime=0;
				sounds.forEach(function(audio){
					audio.pause();
					audio.currentTime=0;
				});
			}
		}
		
		
		this.muteAll = function(){
			if(mute == false){
				mute=true;
				webGL.music.pause();
				webGL.music.currentTime=0;
				sounds.forEach(function(audio){
					audio.pause();
					audio.currentTime=0;
				});
			}
			else{
				webGL.bg_anim.unMuteAll();
			}
		}
		
		this.changeVolume = function(x){
			
				var volume = webGL.music.volume+x;
				if(volume>1){
					volume=1;
				}else if(volume<0.1){
					volume=0.1;
				}
				webGL.music.volume=volume;
				sounds.forEach(function(audio){
					audio.volume=volume;
				});
				transitions.forEach(function(audio){
					audio.volume=volume;
				});
				
		}
		
		this.unMuteAll = function(){
			mute=false;
			webGL.music.play();
		}
		
		this.print = function(GL,MOVEMATRIX, _hasColor, _Mmatrix, _UOpacity, _Color){
			
			GL.uniform1i(_hasColor,0);
			GL.uniform1f(_UOpacity,1);
			GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
			
			
			GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
			
		};
		
		this.print_landscape = function(GL,MOVEMATRIX, _hasColor, _Mmatrix, _UOpacity, _Color){
			LIBS.set_I4(MOVEMATRIX);
			if(bg.transition!=0){
				LIBS.translate(MOVEMATRIX,[bg.animation[0]*bg.transition*bg.transition/60. ,bg.animation[1]*bg.transition*bg.transition/100.,0]);
				LIBS.scale(MOVEMATRIX,[1+bg.transition*bg.transition/500.,1+bg.transition*bg.transition/500.,1]);
				
			}
			LIBS.scale(MOVEMATRIX,[5.5,5.5*window.innerHeight/window.innerWidth,1.]);
			GL.uniform1i(_hasColor,0);
			GL.uniform1f(_UOpacity,0.1-bg.transition*bg.transition/500);
			GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
			
			
			GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
		}
		
		this.printParticles= function(GL,MOVEMATRIX,_Mmatrix,_UOpacity){
			for (var i = 0; i < bg.particlesLight.length; i++) {
				LIBS.set_I4(MOVEMATRIX);
				LIBS.translate(MOVEMATRIX,[bg.particlesLight[i].x ,bg.particlesLight[i].y*2*window.innerHeight/window.innerWidth,0]);
				if(bg.transition!=0){
					LIBS.translate(MOVEMATRIX,[bg.animation[0]*bg.transition/8. ,bg.animation[1]*bg.transition/10.,0]);
				}
				LIBS.scale(MOVEMATRIX,[bg.particlesLight[i].size+Math.cos(bg.particlesLight[i].anim/100.)*0.01,bg.particlesLight[i].size+Math.sin(bg.particlesLight[i].anim/100.)*0.01,1]);
				GL.uniform1f(_UOpacity,Math.sin(bg.particlesLight[i].anim/70)*bg.particlesLight[i].opacity-bg.transition*Math.sign(bg.transition)/100);
				GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
				
				
				GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
			}
		}
		
		this.printParticlesBack= function(GL,MOVEMATRIX,_Mmatrix,_UOpacity){
			for (var i = 0; i < bg.particlesBack.length; i++) {
				LIBS.set_I4(MOVEMATRIX);
				LIBS.translate(MOVEMATRIX,[bg.particlesBack[i].x ,bg.particlesBack[i].y*2*window.innerHeight/window.innerWidth,0]);
				if(bg.transition!=0){
					LIBS.translate(MOVEMATRIX,[bg.animation[0]*bg.transition/8. ,bg.animation[1]*bg.transition/10.,0]);
				}
				LIBS.scale(MOVEMATRIX,[bg.particlesBack[i].size+Math.cos(bg.particlesBack[i].anim/100.)*0.01,bg.particlesBack[i].size+Math.sin(bg.particlesBack[i].anim/100.)*0.01,1]);
				GL.uniform1f(_UOpacity,Math.sin(bg.particlesBack[i].anim/70)*(bg.particlesBack[i].opacity+0.2)/bg.particlesBack[i].size-bg.transition*Math.sign(bg.transition)/100);
				GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
				
				
				GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
			}
		}
		
		this.printBird=function(GL, MOVEMATRIX,_Mmatrix,_UOpacity){
			if(bg.bird!= null){
				LIBS.set_I4(MOVEMATRIX);
				LIBS.translate(MOVEMATRIX,[bg.bird.x ,bg.bird.y*2*window.innerHeight/window.innerWidth,0]);
				if(bg.transition!=0){
					LIBS.translate(MOVEMATRIX,[bg.animation[0]*bg.transition/8. ,bg.animation[1]*bg.transition/10.,0]);
				}
				LIBS.rotateZ(MOVEMATRIX,bg.bird.direction[1]);
				LIBS.scale(MOVEMATRIX,[bg.bird.size+Math.cos(bg.bird.anim/100.)*0.01,bg.bird.size+Math.sin(bg.bird.anim/100.)*0.01,1]);
				GL.uniform1f(_UOpacity,bg.bird.opacity-bg.transition*Math.sign(bg.transition)/100);
				GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
					
					
				GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
			}
		}
		

		this.update = function(GL,MOVEMATRIX){
			bg.update();
			LIBS.set_I4(MOVEMATRIX);
			bg.animate(MOVEMATRIX);
			if(bg.transition>0){
				bg.activecol[3]=1-bg.transition/50.;
				bg2.activecol[3]=bg.transition/50.;
			}
			else{
				bg.activecol[3]=1;
			}
			//LIBS.scale(MOVEMATRIX,[7,3.5,1.]);
			LIBS.scale(MOVEMATRIX,[6,6.5*window.innerHeight/window.innerWidth,1.]);
			var tmp = Math.sin(bg.transition*bg.transition/(4000- 3500*Math.min(0,Math.sign(bg.transition))*Math.sign(bg.transition) )+ 0.*Math.min(0,Math.sign(bg.transition))*Math.sign(bg.transition))/5;
			
			GL.clearColor(bg.activecol[0]*bg.activecol[3] + bg2.activecol[0]*bg2.activecol[3] -tmp,bg.activecol[1]*bg.activecol[3] + bg2.activecol[1]*bg2.activecol[3]-tmp,bg.activecol[2]*bg.activecol[3] + bg2.activecol[2]*bg2.activecol[3]-tmp,1);
			if(bg.transition>=50){
				bg=bg2;
				var color = webGL.randColor();
				bg2=new webGL.Background(color[0],color[1],color[2],0);
				bg.transition=-20;
				this.landscape = Math.floor(Math.random()*6);
			}
			if(!mute){
				var x = Math.floor(Math.random()*500)-500+sounds.length;

				if(x>=0){
					sounds[x].play();
				}
			}
			if(mute == false){
				if(bg.bird==null){
					if(Math.random()<0.0005){
						bg.bird=new webGL.Bird((-4-Math.random()*3)*Math.sign(Math.random()-0.5),-3,Math.random()+0.5);
						transitions[Math.floor(Math.random()*3)+1].play();
					}
				}
				else{
					if(bg.bird.anim>500)
						bg.bird=null;
					else{
						bg.bird.update();
					}
				}
			}

		};
		
		
		this.getColor = function(){
			return bg.activecol;
		};
		
		this.getSounds= function(){
			return sounds;
		};
		
		this.getTransitions= function(){
			return transitions;
		}
		
		
	},
	
	
	Sound_Visualizer: function(){
		var context, canvas,source,ctx,fbc_array, bars, bar_x,bar_width,bar_height;
		
		
		this.load= function(audio,context){
			canvas = document.getElementById('analyser');
			ctx = canvas.getContext('2d');
			source = context.createMediaElementSource(webGL.music);
			source.connect(webGL.analyser);
			for(var i=0; i<webGL.bg_anim.getSounds().length;i++){
				source = context.createMediaElementSource(webGL.bg_anim.getSounds()[i]);
				source.connect(webGL.analyser);
			}
			for(var i=0; i<webGL.bg_anim.getTransitions().length;i++){
				source = context.createMediaElementSource(webGL.bg_anim.getTransitions()[i]);
				source.connect(webGL.analyser);
			}
			webGL.analyser.connect(context.destination);
			
			
			var frameLooper=function(){
				fbc_array = new Uint8Array(webGL.analyser.frequencyBinCount);
				webGL.analyser.getByteFrequencyData(fbc_array);
				ctx.clearRect(0,0,canvas.width,canvas.height);
				ctx.fillStyle = '#FFFFFF';
				bars = 4;
				
				for(var i =0; i<bars;i++){
					bar_x=10+i*canvas.width/3.5;
					bar_width=16;
					bar_height = -(fbc_array[(i+1)*2]/2);
					ctx.fillRect(bar_x,canvas.height,bar_width,bar_height -20);
				}
				window.requestAnimationFrame(frameLooper);
			}
			frameLooper();
			
		};
		
	
		
		
	},
	
	
	randColor : function(){
		var color = [0.1+(Math.random()-0.5)/15.,0.25+(Math.random()-0.5)/15.,0.6+(Math.random()-0.5)/15.];
		var i = Math.floor(Math.random()*100)%3;
		var j=(Math.random()*2-1);
		if(j<0){
			j=-1;
		}
		else
			j=1;
		return [color[i],color[Math.abs((i+j)%3)],color[Math.abs((i-j)%3)],1];
	},

	runWebGL: function(){
		var color = this.randColor();
		webGL.bg_anim = new this.Background_Animation(color[0],color[1],color[2],color[3]);
		webGL.bg_anim.load();
		var CANVAS=document.getElementById("your_canvas");
		
		var add= document.createElement('div');

		add.innerHTML =  '<img id="img1" src ="'+config.imagePath('disableLandscape_tiny.png')+'">';

		document.getElementById("landscape-activ").appendChild(add);
		
		document.getElementById('landscape-activ').onmouseover = function(){
		  TweenLite.to(document.getElementById('landscape-activ'), 1,{opacity:1,zIndex:2});
		};
		document.getElementById('landscape-activ').onmouseleave = function(){
		  TweenLite.to(document.getElementById('landscape-activ'), 0.7,{opacity:0,zIndex:0});
		};
		
		document.getElementById('landscape-activ').onclick = function(){
		  document.getElementById('landscape-activ').removeChild(document.getElementById('img1').parentNode);
		  if(webGL.bg_anim.getLandscape()==true){
			  webGL.bg_anim.setLandscape(false);
			  var add= document.createElement('div');

			  add.innerHTML =  '<img id="img1" src ="'+config.imagePath('enableLandscape_tiny.png')+'">';

			  document.getElementById("landscape-activ").appendChild(add);
		  }
		  else{
			  webGL.bg_anim.setLandscape(true);
			  var add= document.createElement('div');

			  add.innerHTML =  '<img id="img1" src ="'+config.imagePath('disableLandscape_tiny.png')+'">';

			  document.getElementById("landscape-activ").appendChild(add);
		  }
		};
		
		add= document.createElement('div');
		add.innerHTML =  '<img id="img2" src ="'+config.imagePath('swap_tiny.png')+'">';
		document.getElementById("swap").appendChild(add);
		
		
		
		
	  CANVAS.width = window.innerWidth;
		CANVAS.height= window.innerHeight ;

	  /*========================= CAPTURE MOUSE EVENTS ========================= */

	  var AMORTIZATION=0.95;
	  var drag=false;
	  var old_x, old_y;
	  var dX=0, dY=0;


	 
	  /*========================= GET WEBGL CONTEXT ========================= */

	  try {
		GL = CANVAS.getContext("experimental-webgl", {antialias: true});
		GL = CANVAS.getContext("experimental-webgl", {premultipliedAlpha: true});
		GL = CANVAS.getContext("experimental-webgl", { preserveDrawingBuffer: true});
		GL = CANVAS.getContext("experimental-webgl", {alpha : false});
	  } catch (e) {
		alert("You are not webgl compatible :(") ;
		return false;
	  }

	  /*========================= SHADERS ========================= */
	  /*jshint multistr: true */

	  var shader_vertex_source="\n\
	attribute vec3 position;\n\
	uniform mat4 Pmatrix;\n\
	uniform mat4 Vmatrix;\n\
	uniform mat4 Mmatrix;\n\
	attribute vec2 uv;\n\
	varying vec2 vUV;\n\
	void main(void) { //pre-built function\n\
	gl_Position = Pmatrix*Vmatrix*Mmatrix*vec4(position, 1.);\n\
	vUV=uv;\n\
	}";

	  var shader_fragment_source="\n\
	precision mediump float;\n\
	uniform sampler2D sampler;\n\
	uniform int hasColor;\n\
	uniform vec4 Color;\n\
	uniform float UOpacity;\n\
	varying vec2 vUV;\n\
	\n\
	\n\
	void main(void) {\n\
	if(hasColor>0)\n\
		gl_FragColor=Color;\n\
	else\n\
		gl_FragColor = vec4(texture2D(sampler, vUV).xyz,texture2D(sampler, vUV).w*UOpacity);\n\
	}";

	  var get_shader=function(source, type, typeString) {
		var shader = GL.createShader(type);
		GL.shaderSource(shader, source);
		GL.compileShader(shader);
		if (!GL.getShaderParameter(shader, GL.COMPILE_STATUS)) {
		  alert("ERROR IN "+typeString+ " SHADER : " + GL.getShaderInfoLog(shader));
		  return false;
		}
		return shader;
	  };

	  var shader_vertex=get_shader(shader_vertex_source, GL.VERTEX_SHADER, "VERTEX");
	  var shader_fragment=get_shader(shader_fragment_source, GL.FRAGMENT_SHADER, "FRAGMENT");

	  var SHADER_PROGRAM=GL.createProgram();
	  GL.attachShader(SHADER_PROGRAM, shader_vertex);
	  GL.attachShader(SHADER_PROGRAM, shader_fragment);

	  GL.linkProgram(SHADER_PROGRAM);

	  var _Pmatrix = GL.getUniformLocation(SHADER_PROGRAM, "Pmatrix");
	  var _Vmatrix = GL.getUniformLocation(SHADER_PROGRAM, "Vmatrix");
	  var _Mmatrix = GL.getUniformLocation(SHADER_PROGRAM, "Mmatrix");
	  var _hasColor = GL.getUniformLocation(SHADER_PROGRAM, "hasColor");
	  var _Color = GL.getUniformLocation(SHADER_PROGRAM, "Color");
	  var _UOpacity = GL.getUniformLocation(SHADER_PROGRAM, "UOpacity");
	  var _sampler = GL.getUniformLocation(SHADER_PROGRAM, "sampler");
	  var _uv = GL.getAttribLocation(SHADER_PROGRAM, "uv");
	  var _position = GL.getAttribLocation(SHADER_PROGRAM, "position");

	  GL.enableVertexAttribArray(_uv);
	  GL.enableVertexAttribArray(_position);

	  GL.useProgram(SHADER_PROGRAM);
	  GL.uniform1i(_sampler, 0);

	  /*========================= THE CUBE ========================= */
	  //POINTS :
	  var cube_vertex=[
		-1,-1,-1,    0,0,
		1,-1,-1,     1,0,
		1, 1,-1,     1,1,
		-1, 1,-1,    0,1,

	  ];

	  var CUBE_VERTEX= GL.createBuffer ();
	  GL.bindBuffer(GL.ARRAY_BUFFER, CUBE_VERTEX);
	  GL.bufferData(GL.ARRAY_BUFFER,
					new Float32Array(cube_vertex),
		GL.STATIC_DRAW);

	  //FACES :
	  var cube_faces = [
		0,1,2,
		0,2,3,

	  ];
	  var CUBE_FACES= GL.createBuffer ();
	  GL.bindBuffer(GL.ELEMENT_ARRAY_BUFFER, CUBE_FACES);
	  GL.bufferData(GL.ELEMENT_ARRAY_BUFFER,
					new Uint16Array(cube_faces),
		GL.STATIC_DRAW);

	  /*========================= MATRIX ========================= */

	  var PROJMATRIX=LIBS.get_projection(40, CANVAS.width/CANVAS.height, 1, 100);
	  var MOVEMATRIX=LIBS.get_I4();
	  var VIEWMATRIX=LIBS.get_I4();

	  LIBS.translateZ(VIEWMATRIX, -6);
	  var THETA=0,
		  PHI=0;

	  /*========================= TEXTURES ========================= */
	  var get_texture=function(image_URL){


		var image=new Image();


		image.src=image_URL;
		image.webglTexture=false;
		

		image.onload=function(e) {

			

		  var texture=GL.createTexture();
			GL.blendEquation( GL.FUNC_ADD );
			GL.blendFunc(GL.SRC_ALPHA, GL.ONE_MINUS_SRC_ALPHA);
		  GL.pixelStorei(GL.UNPACK_FLIP_Y_WEBGL, true);


		  GL.bindTexture(GL.TEXTURE_2D, texture);

		  GL.texImage2D(GL.TEXTURE_2D, 0, GL.RGBA, GL.RGBA, GL.UNSIGNED_BYTE, image);

		  GL.texParameteri(GL.TEXTURE_2D, GL.TEXTURE_MAG_FILTER, GL.LINEAR);

		  GL.texParameteri(GL.TEXTURE_2D, GL.TEXTURE_MIN_FILTER, GL.NEAREST_MIPMAP_LINEAR);

		GL.texParameteri(GL.TEXTURE_2D, GL.TEXTURE_MIN_FILTER, GL.LINEAR);
		GL.texParameteri(GL.TEXTURE_2D, GL.TEXTURE_WRAP_S, GL.CLAMP_TO_EDGE);
		GL.texParameteri(GL.TEXTURE_2D, GL.TEXTURE_WRAP_T, GL.CLAMP_TO_EDGE);

		  GL.generateMipmap(GL.TEXTURE_2D);

		  GL.bindTexture(GL.TEXTURE_2D, null);

		  image.webglTexture=texture;
		};

		return image;
	  };
	  
	  
	  var cube_texture=[get_texture(config.imagePath('background_medium.png')),get_texture(config.imagePath('background_white_medium.png'))];
	  var particle_texture=[get_texture(config.imagePath('blur_mask_tiny.png')),get_texture(config.imagePath('white_blur_tiny.png'))];
	  var bird_texture=get_texture(config.imagePath('bird_tiny.png'));
	  var landscape_texture=[get_texture(config.imagePath('landscape_1_tiny.png')),get_texture(config.imagePath('landscape_2_tiny.png')),get_texture(config.imagePath('landscape_3_tiny.png')),get_texture(config.imagePath('landscape_4_tiny.png')),get_texture(config.imagePath('landscape_5_tiny.png')),get_texture(config.imagePath('landscape_6_tiny.png')),get_texture(config.imagePath('landscape_7_tiny.png'))];


	  /*========================= DRAWING ========================= */
	  GL.enable(GL.DEPTH_TEST);
	  GL.depthFunc(GL.LEQUAL);
	  
	  GL.enable(GL.BLEND);
	  GL.disable(GL.DEPTH_TEST);

	  GL.clearDepth(1.0);

	  var time_old=0;
	  var animate=function(time) {
		var dt=time-time_old;

		
		time_old=time;
		GL.viewport(0.0, 0.0, CANVAS.width,CANVAS.height );
		PROJMATRIX=LIBS.get_projection(40,  window.innerWidth/window.innerHeight, 1, 100);
		GL.clear(GL.COLOR_BUFFER_BIT | GL.DEPTH_BUFFER_BIT);
			
			
		GL.uniformMatrix4fv(_Pmatrix, false, PROJMATRIX);
		GL.uniformMatrix4fv(_Vmatrix, false, VIEWMATRIX);
		
			
			GL.bindBuffer(GL.ARRAY_BUFFER, CUBE_VERTEX);
			GL.vertexAttribPointer(_position, 3, GL.FLOAT, false,4*(3+2),0) ;
			GL.vertexAttribPointer(_uv, 2, GL.FLOAT, false,4*(3+2),3*4) ;

			GL.bindBuffer(GL.ELEMENT_ARRAY_BUFFER, CUBE_FACES);
			if (cube_texture[0].webglTexture) {
			  GL.activeTexture(GL.TEXTURE0);
			  GL.bindTexture(GL.TEXTURE_2D, cube_texture[0].webglTexture);
			}
			webGL.bg_anim.update(GL,MOVEMATRIX);
			webGL.bg_anim.print(GL,MOVEMATRIX, _hasColor, _Mmatrix,_UOpacity, _Color);
			// var width = document.body.clientWidth;
			// var height = document.body.clientHeight;
			// console.log(width/height);

			if(webGL.bg_anim.getLandscape()){
				if(landscape_texture[webGL.bg_anim.getLandscapeNb()].webglTexture){
					GL.bindTexture(GL.TEXTURE_2D, landscape_texture[webGL.bg_anim.getLandscapeNb()].webglTexture);
				}
				webGL.bg_anim.print_landscape(GL,MOVEMATRIX, _hasColor, _Mmatrix,_UOpacity, _Color);
				
				if(bird_texture.webglTexture){
					GL.bindTexture(GL.TEXTURE_2D, bird_texture.webglTexture);
				}
				webGL.bg_anim.printBird(GL,MOVEMATRIX,_Mmatrix,_UOpacity);
				
			}
			
			if ( particle_texture[1].webglTexture) {
				GL.bindTexture(GL.TEXTURE_2D, particle_texture[1].webglTexture);
			}
			webGL.bg_anim.printParticles(GL,MOVEMATRIX,_Mmatrix,_UOpacity);
			if ( particle_texture[0].webglTexture) {
				GL.bindTexture(GL.TEXTURE_2D, particle_texture[0].webglTexture);
			}
			webGL.bg_anim.printParticlesBack(GL,MOVEMATRIX,_Mmatrix,_UOpacity);
			
			
		
		GL.flush();
		window.requestAnimationFrame(animate);
	  };
	  animate(0);
	  var context = new AudioContext();
	  webGL.analyser = context.createAnalyser();
		
	  webGL.sound_visual = new webGL.Sound_Visualizer();
		
	  webGL.sound_visual.load(webGL.music, context);
	  document.getElementById('analyser').onclick = webGL.bg_anim.muteAll;
	  
	  document.getElementById('analyser').onmouseleave = function(){
		  TweenLite.to(document.getElementById('landscape-activ'), 0.7,{opacity:0,zIndex:0})
		  TweenLite.to(document.getElementById('swap'), 0.5,{opacity:0,zIndex:0})
	  };
	   document.getElementById('analyser').onmouseover = function(){
		  TweenLite.to(document.getElementById('landscape-activ'), 2,{opacity:1,zIndex:2})
		  TweenLite.to(document.getElementById('swap'), 1.3,{opacity:1,zIndex:2})
	  };

	  document.getElementById('analyser').onwheel = function(e){
		  webGL.bg_anim.changeVolume(-Math.sign(e.deltaY)*0.01);

	  };
	},
};
export default webGL;