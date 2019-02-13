'use strict';

var
	_ = require('underscore'),
	
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;

module.exports = {
	ServerModuleName: 'MailChangePasswordPoppassdPlugin',
	HashModuleName: 'mail-poppassd-plugin',
	
	Disabled: false,
	SupportedServers: '',
	Host: '',
	Port: 0,
	
	/**
	 * Initializes settings from AppData object sections.
	 * 
	 * @param {Object} oAppData Object contained modules settings.
	 */
	init: function (oAppData)
	{
		var oAppDataSection = oAppData['%ModuleName%'];
		
		if (!_.isEmpty(oAppDataSection))
		{
			this.Disabled = Types.pBool(oAppDataSection.Disabled, this.Disabled);
			this.SupportedServers = Types.pString(oAppDataSection.SupportedServers, this.SupportedServers);
			this.Host = Types.pString(oAppDataSection.Host, this.Host);
			this.Port = Types.pNonNegativeInt(oAppDataSection.Port, this.Port);
		}
	},
	
	/**
	 * Updates new settings values after saving on server.
	 * 
	 * @param {array} aSupportedServers
	 * @param {string} sHost
	 * @param {number} iPort
	 */
	updateAdmin: function (aSupportedServers, sHost, iPort)
	{
		this.SupportedServers = Types.pString(aSupportedServers, this.SupportedServers);
		this.Host = Types.pString(sHost, this.Host);
		this.Port = Types.pNonNegativeInt(iPort, this.Port);
	}
};
