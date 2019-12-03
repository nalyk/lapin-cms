var user = this;
var roles = [];
if(this.roles) {
    this.roles.forEach(function(r) {
        dpd.roles.get({id: r}, function(targetRole) {
            roles.push(targetRole);
        });
    });
}
user.roles = roles;