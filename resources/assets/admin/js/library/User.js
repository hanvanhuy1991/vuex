/**
 * Class User Rule and Permission
 *
 * @export
 * @class User
 */
export default class User {
    /**
     * Creates an instance of User.
     * @param {*} raw
     * @memberof User
     */
    constructor(raw) {
        this.raw = raw;
    }

    /**
     * User Can Something
     *
     * @param {*} permissions
     * @param {boolean} [requireAll=false]
     * @return {*}
     * @memberof User
     */
    can(permissions, requireAll = false) {
        if (this.raw) {
            if (Array.isArray(permissions)) {
                for (let i in permissions) {
                    let hasPermision = this.can(permissions[i]);
                    if (hasPermision && !requireAll) {
                        return true;
                    } else if (!hasPermision && requireAll) {
                        return false;
                    }
                }
            }
            for (let i in this.raw.roles) {
                let role = this.raw.roles[i];
                for (let j in role.permissions) {
                    if (permissions === role.permissions[j].name) {
                        return true;
                    }

                }
            }

            return false;
        }

        return false;
    }

    /**
     * User Hasrole
     *
     * @param {*} roles
     * @param {boolean} [requireAll=false]
     * @return {*}
     * @memberof User
     */
    hasRole(roles, requireAll = false) {
        if (this.raw) {
            if (Array.isArray(roles)) {
                for (let i in roles) {
                    let hasRole = this.hasRole(roles[i]);
                    if (hasRole && !requireAll) {
                        return true;
                    } else if (!hasRole && requireAll) {
                        return false;
                    }
                }
            }

            for (let i in this.raw.roles) {
                let role = this.raw.roles[i];
                if (roles === role.name) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}