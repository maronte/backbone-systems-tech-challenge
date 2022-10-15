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

c_estado -> key numeric(2)

?        -> code ?

**b. Municipios -> municipality**

D_mnpio -> name string(100) (mayusculas 
sin caracteres especiales)

c_mnpio -> key numeric(3)

**c. Codigo Postal -> zip_code**

d_codigo -> zip_code string(5)

d_ciudad -> locality string(100) (mayusculas sin caracteres especiales)


**d. Asentamiento settlement **

d_asenta -> name string(100) (mayusculas sin caracteres especiales)

id_asenta_cpcons -> key numeric(4) (se repiten no puede ser la llave primaria)

d_zona -> zone_type char(1) (propiedad calculada convertir el caracter a su correspondiente en ENUM URBANO o RURAL)

e. Tipo de asentamiento -> settlement_type

d_tipo_asenta -> name string(20)

3. ### Se define un diagrama entidad-relación para la creacion de la base de datos.

![Diagrama Entidad Relacion](/doc/assets/der.png)

4. ### Se crean las migraciones en el proyecto de laravel.

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