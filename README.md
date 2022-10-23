# Backbone systems, pruebas técnica

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