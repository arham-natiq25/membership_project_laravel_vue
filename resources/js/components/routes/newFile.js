import axios from 'axios';

export default (await import('vue')).defineComponent({
name: "Route",
data() {
return {
totalroutes: [],
locations: [],
routes: {
title: "",
short_description: "",
long_description: "",
locations_id: []
},
successMessage: "",
errors: {},
temp_id: null,
isEditing: false
};
},
mounted() {
this.getLocations();
this.getRoutes();
},
methods: {
getLocations() {
axios.get('/api/locations').then(res => {
this.locations = res.data;
});
},
saveRoutes() {
let method = axios.post;
let url = '/api/routes';

if (this.isEditing) {
method = axios.put;
url = `/api/routes/${this.temp_id}`;
}

try {
method(url, this.routes).then(async (res) => {
this.routes = {
title: "",
short_description: "",
long_description: "",
locations_id: []
},
this.successMessage = res.data.message;
this.isEditing = false;
this.temp_id = null;
this.errors = {};
setTimeout(() => {
this.successMessage = '';
}, 5000);
}).catch(error => {
if (error.response && error.response.data) {
const { data } = error.response;
if (data.errors) {
this.errors = data.errors;
}
}
});
} catch (error) {
console.log(error);
}
},
getRoutes() {
axios.get('/api/routes').then(res => {
this.totalroutes = res.data;
});
},
editRoutes(data) {
this.routes = {
title: data.title,
short_description: data.short_description,
long_description: data.long_description,
locations_id: data.locations_id
};
this.temp_id = data.id,
this.isEditing = true,
this.createTripForm = true;
},
}
});
