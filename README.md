**Requirement**:
-     php 7.4
-     laravel 6.x
-     mongoddb v4.2.8
-     Create Mongodb database 
-     change : MONGO_DB_DATABASE in MARKETPLACE/.env to use your database    
-     Create clients Collection 

    




**clients Collection** :
                client{
                    CIN,
                    Nom
                }

                You can change or add some attributes in app/Client.php ($fillable)




**routes/api/clients **
 http://127.0.0.1:8000/api/clients


**Functionalities :**           **Action **                 **Body/row(exempl) **          ** Url **

> get All Clients             get                                                              http://127.0.0.1:8000/api/clients


> get client by Id            get                                                              http://127.0.0.1:8000/api/clients/{id}


> `delete client`             delete                                                           http://127.0.0.1:8000/api/clients/{id}


> update client               put                     {"CIN":"AB12345","Nom":"hamdani"}         http://127.0.0.1:8000/api/clients/{id}
                                                     
                                                          


> Insert client               Post                    {"CIN":"AB456","Nom":"erraji}              http://127.0.0.1:8000/api/clients
                                                    


# backend-marketplace-espece
