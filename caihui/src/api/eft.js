// 控制地址跳转地址

let eft;
let url=document.domain;

if(url == "caihui.test.colourlife.com"){//测试

  	eft = 'http://efees.test.colourlife.com/redirect';

}else if(url == "caihui.colourlife.com"){//正式

  	eft = 'http://eft.colourlife.com/redirect';

}else if(url=="localhost"){//本地

 	 eft = 'http://efees.test.colourlife.com/redirect';

}else if(url == "evisit-czybeta.colourlife.com"){//预发

  	eft = 'http://eft.colourlife.com/redirect';
  	
}

export {eft}
