export default class PrepairDB{

    async getData(){
        
        let login = document.head.querySelector('meta[name="basic-auth"]').content;
        
        await localforage.length().then(function(numberOfKeys) {
            //check exist key
            if(numberOfKeys < 2){
                console.log('PrepairDB setItem')
                axios.get('api/employee/info/' + login).then(response => {
                    localforage.setItem('userInfo', response.data[0]).catch(function(err) {
                        console.log(err);
                    });
                });
        
                axios.get('api/employee/isAdmin/' + login).then(response => {
                    localforage.setItem('isAdmin', response.data).catch(function(err) {
                        console.log(err);
                    });
                });  
            }
        })
    }
    constructor(){
        /**
         * Prepair Data to Indexed DB
         */

        console.log('PrepairDB')

        localforage.setDriver([localforage.INDEXEDDB, localforage.LOCALSTORAGE]);
        
        this.getData();
              
    }
}