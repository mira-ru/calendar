(function(require){
	var Utils = {
		// Public
		options: {
			id: 1
		}
	};

	Utils.timestamp2date = function(timestamp){
    		var theDate = new Date(timestamp * 1000); 
    		return theDate.toGMTString(); 
	}

	// Export
	return Utils;
})