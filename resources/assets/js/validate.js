var rulesHandlers = {};

Vue.directive('validate', function (el, binding, vnode, oldVnode) {
    rulesHandlers[el.getAttribute('id')] && oldVnode.data.domProps && vnode.data.domProps.value != oldVnode.data.domProps.value && rulesHandlers[el.getAttribute( 'id')](vnode.data.domProps.value)
})

function Validator(rules, state) {
    for (const ruleName in rules) {
        rulesHandlers[ruleName] = function (value) {
            Vue.set(state, ruleName, rules[ruleName].test(value))
        }
    }
    function isValid() {
        return Object.keys(state).length > 0 && (Object.keys(state).filter(ruleName => state[ruleName] == false)).length < 1;
    }
    return {
        isValid
    }
}
export default Validator