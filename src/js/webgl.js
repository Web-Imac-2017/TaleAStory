'use strict';

import LIBS from './libs.js';
import config from './config.js';

let webGL={
	bg_anim: null,
	 Background: function(r,v,b,a){
		this.activecol=[r,v,b,a];

		this.animation=[0,0,0];
		this.transition=0;
		
		this.addActiveCol = function (r,v,b,a) {
			this.activecol=[r,v,b,a];
		};
		

		this.move = function(x,y,z){
			this.animation=[x,y,z];
			if(this.transition==0)
				this.transition=1;
		};
		
		this.update = function(){
			if(this.transition <100 && this.transition>0)
				this.transition++;

		};
		
		this.animate= function(m){
			if(this.transition!=0){
				LIBS.translate(m,[this.animation[0]*this.transition/8. ,this.animation[1]*this.transition/10.+ this.animation[2]*this.transition/20.,0]);
				LIBS.scale(m,[1+this.transition/50.,1+this.transition/50.,1]);
				
			}

		};
	},

	Background_Animation: function(r,v,b,a){
		var bg = new webGL.Background(r,v,b,a);
		var bg2 = new webGL.Background(Math.random(),Math.random(),Math.random(),1);
		
		
		this.getTransition= function(){
			return bg.transition;
		};
		
		
		this.move=function(x,y,z){
			bg.animation=[x,y,z];
			if(bg.transition==0)
				bg.transition=1;
			
		};
		
		
		this.print = function(GL,MOVEMATRIX, _hasColor, _Mmatrix, _UOpacity, _Color){
			
			GL.uniform1i(_hasColor,0);
			GL.uniform1f(_UOpacity,1-bg.transition/100);
			GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
			
			
			GL.drawElements(GL.TRIANGLES, 2*3, GL.UNSIGNED_SHORT, 0);
			
		};
		

		this.update = function(GL,MOVEMATRIX){
			bg.update();
			LIBS.set_I4(MOVEMATRIX);
			bg.animate(MOVEMATRIX);
			bg.activecol[3]=1-bg.transition/100.;
			bg2.activecol[3]=bg.transition/100.;
			LIBS.translateX(MOVEMATRIX,-0.4);
			LIBS.scale(MOVEMATRIX,[7.5,3,1.]);
			
			
			GL.clearColor(bg.activecol[0]*bg.activecol[3] + bg2.activecol[0]*bg2.activecol[3],bg.activecol[1]*bg.activecol[3] + bg2.activecol[1]*bg2.activecol[3],bg.activecol[2]*bg.activecol[3] + bg2.activecol[2]*bg2.activecol[3],1);

			if(bg.transition>=100){
				bg=bg2;
				bg2=new Background(Math.random(),Math.random(),Math.random(),0);
			}
		};
		
	},


	runWebGL: function(){
		webGL.bg_anim = new this.Background_Animation(0.2,0.,0.6,1.);
		var CANVAS=document.getElementById("your_canvas");
	  CANVAS.width=window.innerWidth;
	  CANVAS.height=window.innerHeight;

	  /*========================= CAPTURE MOUSE EVENTS ========================= */

	  var AMORTIZATION=0.95;
	  var drag=false;
	  var old_x, old_y;
	  var dX=0, dY=0;


	 
	  /*========================= GET WEBGL CONTEXT ========================= */
	  var GL;
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
	  
	  var cube_texture=get_texture(config.imagePath('background_large.png'));


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
		webGL.bg_anim.update(GL,MOVEMATRIX);
		GL.viewport(0.0, 0.0, CANVAS.width, CANVAS.height);
		GL.clear(GL.COLOR_BUFFER_BIT | GL.DEPTH_BUFFER_BIT);
			
			
		GL.uniformMatrix4fv(_Pmatrix, false, PROJMATRIX);
		GL.uniformMatrix4fv(_Vmatrix, false, VIEWMATRIX);
		GL.uniformMatrix4fv(_Mmatrix, false, MOVEMATRIX);
		
			
			if (cube_texture.webglTexture) {

			  GL.activeTexture(GL.TEXTURE0);

			  GL.bindTexture(GL.TEXTURE_2D, cube_texture.webglTexture);
			}
			GL.bindBuffer(GL.ARRAY_BUFFER, CUBE_VERTEX);
			GL.vertexAttribPointer(_position, 3, GL.FLOAT, false,4*(3+2),0) ;
			GL.vertexAttribPointer(_uv, 2, GL.FLOAT, false,4*(3+2),3*4) ;

			GL.bindBuffer(GL.ELEMENT_ARRAY_BUFFER, CUBE_FACES);
		
		webGL.bg_anim.print(GL,MOVEMATRIX, _hasColor, _Mmatrix,_UOpacity, _Color);
		
		GL.flush();
		window.requestAnimationFrame(animate);
	  };
	  animate(0);
	},
};
export default webGL;