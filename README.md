# CPharma v.6.0.1 Latest commit Server 37923ab

***

## Estándares para el desarrollo del CPharma

***

## 1. Estándar para la estructura en general

* Layouts
* Includes
* Styles (Internos)
* Logica de programacion (Frontend - Backend)
* Funciones (Internas)
* Querys (Internos)

***

## 2. Tratamiento para variables

* Variables de tipo int: **`intval($variable);`**
* Precios: **`number_format($variable,2,"," ,"." );`**
* Fechas: **`format('d-m-Y');`**
* Descripciones: **`FG_Limpiar_Texto($variable);`**

***

## 3. Estándar para la asignación de nombres (Caso General)

* Para Funciones: **FG**_ Nombre_Funcion()
* Para Querys: **QG**_ Nombre_Query()

##### Observación: 

* Se debe **respetar estrictamente** las siglas marcadas como **XX**.
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

***

## 4. Estándar para la asignación de nombres (Caso Reportes)

* Para Reportes: **R#**_ Nombre_Reporte()
* Para Funciones: **R#F**_ Nombre_Funcion()
* Para Querys: **R#Q**_ Nombre_Query()

##### Observación: 

* Se debe **respetar estrictamente** las siglas marcadas como **XX**.
* Se debe **interpretar estrictamente** el símbolo **(#)** como el numero del reporte en cuestión. Ej.: R8 (para el reporte numero 8)
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

***

## 5. Estándar para la asignación de nombres (Caso Temporales) [**NO RECOMENDADO**]

* Para Tablas Temporales: **CP**_ Nombre_Tabla()

##### Observación: 

* Se debe **respetar estrictamente** las siglas marcadas como **XX**.
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

***

## 6. Estándar para la asignación de nombres (Caso GIT)

* Master: **QUEDA PROHIBIDO** trabajar sobre la rama **MASTER**.
* MasterDP: **DP**_ Nombre_Rama()
* Ramas: **DP**_ Nombre_Rama()

##### Observación: 

* Se debe **interpretar estrictamente** las siglas **DP**, como las siglas del departamento en cuestión. Ej.: RH (Recursos Humanos)
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

***

## 7. Estándar para la asignación de nombres (Caso MVC)

* Modelo: **DP**_ Nombre_Modelo()
* Controlador: **DP**_ Nombre_Controlador()
* Migraciones: **DP**_Nombre _Tabla _Data _Base()

##### Observación: 

* Se debe **interpretar estrictamente** las siglas **DP**, como las siglas del departamento en cuestión. Ej.: RH (Recursos Humanos)
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

***

## 8. Estándar para la asignación de nombres (Caso Estilos CPharma - Estilos Internos)

* Para Estilos: **CP**-Nombre-Estilo{}

##### Observación: 

* Se debe **respetar estrictamente** las siglas marcadas como **XX**.
* Se debe **respetar estrictamente** el símbolo **(_)** para separa cada palabra que conforma el nombre de la función.
* Se debe **respetar estrictamente** la letra en **MAYÚSCULA** al inicio de cada palabra.

