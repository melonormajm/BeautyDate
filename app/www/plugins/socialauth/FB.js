var fbapi = {
    authorize: function (options) {

        var deferred = $.Deferred();

        //Build the OAuth consent page URL
        var authUrl = 'https://www.facebook.com/dialog/oauth?' + $.param({
            client_id: options.client_id,
            client_secret: options.client_secret,
            redirect_uri: options.redirect_uri
        });

        console.log('about to open InAppBrowser with URL: ' + authUrl);

        //Open the OAuth consent page in the InAppBrowser
        var authWindow = window.open(authUrl, '_blank', 'location=no,toolbar=no');
        //var authWindow = window.open(authUrl, '_blank');

        $(authWindow).on('loadstart', function (e) {
            var url = e.originalEvent.url;

            console.log('InAppBrowser: loadstart event has fired with url: ' + url);

            var code = /\?code=(.+)$/.exec(url);
            var error = /\?error=(.+)$/.exec(url);


            //Esto lo puse yo:
            alert("Entro por code: " + code);
            alert("Entro por error: " + error);
            localStorage.setItem("beautyDateAuthType_FB: code +", url);

            if (code || error) {
                //Always close the browser when match is found
                authWindow.close();
            }



            if (code) {
                //Exchange the authorization code for an access token
                alert("Exchange the authorization code for an access token: code["+ code [1] + "]");

                $.post('https://graph.facebook.com/oauth/access_token', {
                    code: code[1],
                    client_id: options.client_id,
                    client_secret: options.client_secret,
                    redirect_uri: options.redirect_uri
                }).done(function (data) {
                    alert("access_token" + data);
                    deferred.resolve(data);
                }).fail(function (response) {
                    deferred.reject(response.responseJSON);
                });
            } else if (error) {
                //The user denied access to the app
                deferred.reject({
                    error: error[1]
                });
            }
        });

        return deferred.promise();
    },

    profile: function (access_token) {
        var deferred = $.Deferred();

        //Build the OAuth consent page URL
        var profile_uri = 'https://graph.facebook.com/me?' + access_token + "&fields=id,name,link,email,firstname,lastname";

        console.log('about to fetch facebook profile: ' + profile_uri);

        $.getJSON(profile_uri)
        .done(function (data) {
            //var str = "";
            //str = "id[" + data.id + "]" + ", name[" + data.name + "]" + ", email[" + data.email + "]";



            //alert(str);
            console.log(data);
            deferred.resolve(data);
        }).fail(function (response) {
            deferred.reject(response.responseJSON);
        });

        return deferred.promise();
    }
};