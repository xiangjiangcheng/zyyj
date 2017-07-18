$.extend($.fn.validatebox.defaults.rules, {
    equals: {
		validator: function(value,param){
			return value == $(param[0]).val();
		},
		message: '密码不一致'
    },
    noequals: {
        validator: function(value,param){
            return !(value == $(param[0]).val());
        },
        message: '新旧密码不能相同'
    },
        minLength: {
		validator: function(value, param){
			return value.length >= param[0];
		},
		message: '不小于{0}个字符'
    },
    mobile:{
    	validator:function(value){
    		return /^1[3|4|5|7|8]\d{9}$/.test(value);
    	},
    	message:'请输入合法手机号'
    }
});