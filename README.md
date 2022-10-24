# Backbone systems, pruebas técnica

## Descripción

Este proyecto es una prueba técnica con instrucciones disponibles en [este link](https://jobs.backbonesystems.io/challenge/1) y consta de una API de un solo endpoint que como recurso base es un objeto que detalla un código postal de la México. Contiene todos los códigos postales existentes gracias a la información recabada de [Correos de México](https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx). 

Cada código postal contiene un estado, un municipio, un conjunto de asentamientos pertenecientes al código postal y cada asentamiento tiene su tipo.

Este proyecto tiene un lector de CSV personalizado para acelerar el llenado de la base de datos, junto con un procesador de filas del csv que aprovecha el hecho de que los datos están ordenados para de igual manera acelerar el proceso de llenado del CSV sin saturar la memoria del hardware que ejecute la app.

### Lector de CSV

El lector de CSV aprovecha el uso de los generadores, para leer una fila por iteración, pero además permite acumular una cantidad de filas dada para acelerar el proceso de inserción en la base de datos, ya que leyendo uno a uno se tendrían que generar hasta 5 queries de inserción por cada fila del CSV y otras adicionales para verificar que el registro dado no exista.

### Procesador del filas del archivo CSV

El procesador del CSV es una clase que formatea los datos a lo esperado y marcado en el ejemplo de la prueba técnica, a su vez mapea los datos del csv a propiedades del modelo de la base de datos definido (Disponible en la descripción de resolución del reto). Gracias a que los datos están ordenados por estado, municipio, y codigo postal puede verificar qué modelos ya se procesaron y cuales no para clasificarlos y acumularlos por tipo para hacer inserciones masivas que permitan acelerar el tiempo de procesamiento del archivo.

### Database seeder

Se construyó un database seeder personalizado haciendo uso de las anteriores features que lee 1000 filas por iteración e inserta en masa los registros para cada tabla y en orden para respetar las relaciones de las tablas.

#### Servicio REST de códigos postales
### Recursos API

Se añadieron recursos API de laravel para formatear los modelos de base de datos a el recurso expuesto en la API que se espera según la prueba técnica. A su vez cada recurso contiene documentación para poderlos representar en documentación de API con Swagger.

### Controlador

El controlador del servicio rest valida con una expresión regular que el codigo postal dado sea realmente un codigo postal, para este caso sería mejor retornar un error de tipo 400, pero la prueba indica 502 para esos casos y por eso se mantuvo ese error.

Para consultar la información de la base de datos se uso el ORM y las relaciones mediante eager loading. Se hace uso del metodo where en lugar de findOrFail para evitar conflictos con el tipo de dato del id de codigos postales, ya que es un string. 

Para el caso de errores 404 se hace explicítamente la comprobación de existencia del recurso ya que el metodo que firstOrFail no retornar directamente un error 404, pero en caso de crecer el sistema fácilmente se puede hacer el refactor para retornar un 404 automáticamente con el tipo de excepción retornada por dicho método. De igual manera sería más conveniente retornar JSON para estos errores pero por fines de pruebas y para mantener la compatibilidad con el ejemplo se dejarán como respuestas HTML.

### Testing

Cada feature o clase desarrollada para el proyecto tiene su correspondiente test de integración o unitario haciendo uso del framework pest (una simplificación de PHPUnit).

## Como usar el proyecto

### Setup del proyecto

Correr los siguientes comandos si es la primera vez:

``` shell
$ composer install # Instala dependencias
$ ./vendor/bin/sail up # Configuracion inicial de docker con sail
$ alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail' # Crea alias para laravel sail
$ sail artisan migrate # Configura la base de datos
$ sail artisan db:seed # Llena la base de datos con la información de base de datos
```

Para volver a correr el servicio unicamente hay que correr:
``` shell
$ ./vendor/bin/sail up # Configuracion inicial de docker con sail
```

Para correr los tests solamente (con el proyecto ya iniciado) basta con correr el comando:
La base de datos de tests es sqlite en memoria, por lo que no entra en conflicto con la base de datos de desarrollo local.
``` shell
$ sail test
```

### CI/CD

Este proyecto se encuentra alojado en github y corre automáticamente todos los tests
con un workflow al crear un PR en la rama main, de igual manera formatea automáticamente el proyecto con la dependencia pint. Una vez que se hace un commit en la rama main el proyecto será automáticamente actualizado en railway.app

## Proceso de resolución

1. ### Analizar fuente de datos.

La fuente de datos es un archivo que contiene codigos postales e informacion relacionada a estos. Asentamientos con su tipo y nombre, nombre de la localidad del CP, municipios y estados.
La fuente de datos puede ser un excel, un xml o un archivo tipo csv. Se utilizará este último ya que facilita el procesamiento de la información.

2. ### Definir modelos y base de datos

Se definirá un esquema de base de datos para darle una correcta estructura y mantenibilidad a los datos del sistema dado que se usará una base de datos tipo
SQL y son las habilidades esperadas de la pruebas.

Una base de datos tipo NoSQL como mongoDB y redundancia de datos pueden permitir una mayor velocidad de consulta pero dado que la información en general es "estática" (salvo actualizaciones pequeñas posibles por cambios en la geografía no recurrentes) y no es "big data" considero que una correcta normalización de la BD dadas las relaciones sencillas y los ids de tipo númerico permitirán que los joins sean rápidos y así tener un rendimiento de consulta bajo los tiempos límites establecidos. Ademas una base de datos tipo nosql retira mucho soporte de las funcionalidades de laravel y dificulta la mantebilidad de los datos por la redundancia.

### Data Mapping

**a. Estados -> federal_entity**

d_estado -> name string(50) (mayusculas sin caracteres especiales)

c_estado -> id numeric(2) (Puede ser id porque no se repite)

?        -> code ?

**b. Municipios -> municipality**

D_mnpio -> name string(100) (mayusculas 
sin caracteres especiales)

c_mnpio -> key numeric(3) (No puede ser id ya que se reinicia la numeración por estado)

**c. Codigo Postal -> zip_code**

d_codigo -> id string(5) (Puede ser id ya que es unico)

d_ciudad -> locality string(100) (mayusculas sin caracteres especiales)


**d. Asentamiento settlement **

d_asenta -> name string(100) (mayusculas sin caracteres especiales)

id_asenta_cpcons -> key numeric(4) (se repiten no puede ser la llave primaria)

d_zona -> zone_type char(1) (propiedad calculada convertir el caracter a su correspondiente en ENUM URBANO o RURAL)

e. Tipo de asentamiento -> settlement_type

d_tipo_asenta -> name string(20)
c_tipo_asenta -> id numeric(3,0) (puede ser llave primaria ya que no repiten)

3. ### Se define un diagrama entidad-relación para la creacion de la base de datos.

![Diagrama Entidad Relacion](/doc/assets/der.png)

4. ### Se crean las migraciones en el proyecto de laravel.

5. ### Se crea un lector del archivo CSV.

El lector de CSV tendrá un generador que retorna una fila por cada iteración para no saturar la memoria del servidor. Adicionalmente a esto, el lector también tendrá un generador que retornar la cantidad de filas del csv dadas (1000) por defecto para poder insertar los registros de forma masiva y reducir el tiempo de procesamiento a base de datos.

6. ### Se transforma el archivo CSV para tener la codificación UTF-8

El archivo dado tiene una codificación ISO-8859-1
que fue detectada con el comando:

``` bash
file --mime-encoding {rutaArchivo}
```

Y para parsearlo a UTF-8 y trabajar más fácilmente con el archivo se utilizó el siguiente comando:
``` bash
iconv --from-code=iso-8859-1 --to-code=utf-8 {rutaArchivo} > {rutaNuevoArchivo}
```

7. ### Se crea un procesador del archivo CSV.

Dado que el archivo está ordeneado por todos los campos que nos interesan de él podemos iterar secuencialmente el archivo y una podemos estar seguro que una vez que cambia el estado de una fila a otra todos los asentamientos de ese estado fueron procesados, no habrá un asentamiento de ese estado posteriormente en el archivo. Este orden de igual manera aplica con municipio y codigo postal.
También es válido suponer que los tipos de asentamiento son pocos, por el análisis del archivo de información. 

Dados los hallazgos anteriormente mencionados podemos decir que no es necesario hacer consultas a la base de datos para saber que registros ya fueron insertados, por lo que podemos programar un procesador de fila del csv para poder acumular los registros y hacer inserciones masivas que aceleren el procesado de la información, de no ser así un insert por fila alentaría mucho el proceso.

Dado un procesador de filas del csv este limpiará los datos al formato correcto y luego responderá qué entidades añadir a la lista de inserciones que no han sido procesadas.

8. ### Database seeder

Se crea un database seeder para insertar en la base de datos todos los registros
procesados por el preprocesador de información. Agrupa todos los registros por tabla
y de acuerdo al orden de creación de tablas (para no tener conflictos de llaves foráneas)
se insertan los registros en masa por tabla.

9. ### Crear relaciones en los modelos y exponer la consulta como endpoint.

Se añadirán las relaciones en los modelos para poder exponer la consulta de codigos postales como un endpoint con la estructura definida.

10. ### Crear recursos laravel para estructurar la respuesta del servicio según lo esperado.

Una vez creada la consulta correctamente a la base de datos de codigos postales se crean recursos de laravel para darle el formato esperado según la prueba técnica.

11. ### Se implementa swagger como documentación de la API como parte de las buenas practicas.

### Ejemplo de respuesta esperada

``` JSON
{
  "zip_code": "64030",
  "locality": "MONTERREY",
  "federal_entity": {
    "key": 19,
    "name": "NUEVO LEON",
    "code": null
  },
  "settlements": [
    {
      "key": 16,
      "name": "CHEPEVERA",
      "zone_type": "URBANO",
      "settlement_type": {
        "name": "Colonia"
      }
    },
    {
      "key": 17,
      "name": "LOMAS",
      "zone_type": "URBANO",
      "settlement_type": {
        "name": "Colonia"
      }
    }
  ],
  "municipality": {
    "key": 39,
    "name": "MONTERREY"
  }
} 
```
