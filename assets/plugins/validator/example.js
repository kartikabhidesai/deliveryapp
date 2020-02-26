$(document).ready(function() {
	$('#FormExample1').bootstrapValidator();
	
	$('#FormExample2').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			username: {
				message: 'The username is not valid',
				validators: {
					notEmpty: {
						message: 'The username is required and can\'t be empty'
					},
					stringLength: {
						min: 6,
						max: 30,
						message: 'The username must be more than 6 and less than 30 characters long'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9_\.]+$/,
						message: 'The username can only consist of alphabetical, number, dot and underscore'
					}
				}
			},
			email: {
				validators: {
					notEmpty: {
						message: 'The email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'The input is not a valid email address'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'The password is required and can\'t be empty'
					},
					identical: {
						field: 'confirmPassword',
						message: 'The password and its confirm are not the same'
					}
				}
			},
			confirmPassword: {
				validators: {
					notEmpty: {
						message: 'The confirm password is required and can\'t be empty'
					},
					identical: {
						field: 'password',
						message: 'The password and its confirm are not the same'
					}
				}
			}
		}
	});
	
	


 $('#FormExample3').bootstrapValidator({
	message: 'This value is not valid',
	feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	},
	fields: {
		monthDayYear: {
			validators: {
				date: {
					format: 'MM/DD/YYYY'
				}
			}
		},
		yearDayMonth: {
			validators: {
				date: {
					format: 'YYYY-DD-MM'
				}
			}
		},
		monthDayYearTime: {
			validators: {
				date: {
					format: 'MM/DD/YYYY h:m A'
				}
			}
		},
		yearDayMonthTime: {
			validators: {
				date: {
					format: 'YYYY-DD-MM h:m A'
				}
			}
		}
	}
});



	$('#addUser').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
		/*	username: {
				message: 'The username is not valid',
				validators: {
					notEmpty: {
						message: 'The username is required and can\'t be empty'
					},
					stringLength: {
						min: 6,
						max: 30,
						message: 'The username must be more than 6 and less than 30 characters long'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9_\.]+$/,
						message: 'The username can only consist of alphabetical, number, dot and underscore'
					}
				}
			},*/
			username: {
				validators: {
					notEmpty: {
						message: 'The username is required and can\'t be empty'
					}
				}
			},
			fullName: {
				validators: {
					notEmpty: {
						message: 'The full name is required and can\'t be empty'
					}
				}
			},
			country: {
				validators: {
					notEmpty: {
						message: 'The country is required and can\'t be empty'
					}
				}
			},
			district: {
				validators: {
					notEmpty: {
						message: 'The district is required and can\'t be empty'
					}
				}
			},
			districtId: {
				validators: {
					notEmpty: {
						message: 'The district is required and can\'t be empty'
					}
				}
			},
			city: {
				validators: {
					notEmpty: {
						message: 'The city is required and can\'t be empty'
					}
				}
			},
			street: {
				validators: {
					notEmpty: {
						message: 'The street is required and can\'t be empty'
					}
				}
			},
			address: {
				validators: {
					notEmpty: {
						message: 'The address is required and can\'t be empty'
					}
				}
			},
			email: {
				validators: {
					notEmpty: {
						message: 'The email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'The input is not a valid email address'
					}
				}
			},
			website: {
				validators: {
					uri: {
						message: 'The input is not a valid URL'
					}
				}
			},
			phone: {
				validators: {
					digits: {
						message: 'The value can contain only digits'
					}
				}
			},
			color: {
				validators: {
					hexColor: {
						message: 'The input is not a valid hex color'
					}
				}
			},
			zipcode: {
				validators: {
					zipcode: {
						country: 'US',
						message: 'The input is not a valid US zip code'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'The password is required and can\'t be empty'
					},
					identical: {
						field: 'confirmPassword',
						message: 'The password and its confirm are not the same'
					}
				}
			},
			confirmPassword: {
				validators: {
					notEmpty: {
						message: 'The confirm password is required and can\'t be empty'
					},
					identical: {
						field: 'password',
						message: 'The password and its confirm are not the same'
					}
				}
			},
			ages: {
				validators: {
					lessThan: {
						value: 100,
						inclusive: true,
						message: 'The ages has to be less than 100'
					},
					greaterThan: {
						value: 10,
						inclusive: false,
						message: 'The ages has to be greater than or equals to 10'
					}
				}
			}
			,
			acceptterms: {
				validators: {
					notEmpty: {
						message: 'You have to accept the terms and policies'
					}
				}
			}
		}
	});
	
	$('#addProduct').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			
			prdName: {
				validators: {
					notEmpty: {
						message: 'The product name is required and can\'t be empty'
					}
				}
			},
			
			prdTypeId: {
				validators: {
					notEmpty: {
						message: 'The product category is required and can\'t be empty'
					}
				}
			},
			prdQty: {
				validators: {
					notEmpty: {
						message: 'The product quantity is required and can\'t be empty'
					}
				}
			},
			qtyUnitId: {
				validators: {
					notEmpty: {
						message: 'The quantity unit is required and can\'t be empty'
					}
				}
			},
			prdUnitPrice: {
				validators: {
					notEmpty: {
						message: 'The product price is required and can\'t be empty'
					}
				}
			}
		}
	});
	
	$('#addDistrict').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			districtCode: {
				validators: {
					notEmpty: {
						message: 'The District code is required and can\'t be empty'
					}
				}
			},
			districtName: {
				validators: {
					notEmpty: {
						message: 'The District name is required and can\'t be empty'
					}
				}
			}
		}
	});
	
	$('#changepassword').bootstrapValidator({
		message: 'This value is not valid',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			currentpassword: {
				validators: {
					notEmpty: {
						message: 'Current password can\'t be empty'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'The password is required and can\'t be empty'
					},
					identical: {
						field: 'cnfpassword',
						message: 'The password and its confirm are not the same'
					}
				}
			},
			cnfpassword: {
				validators: {
					notEmpty: {
						message: 'The confirm password is required and can\'t be empty'
					},
					identical: {
						field: 'password',
						message: 'The password and its confirm are not the same'
					}
				}
			}
		}
	});
});