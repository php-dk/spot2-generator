Generator code for Spot2

параметры:
   extendEntity - название класса наследника по умолчанию Spot\Entity
   extendMapper - название класса наследника по умолчанию Spot\Mapper
    
   isMapper     - создавать ли Mapper класс (в текущем файле)
   dir          - директория, по умолчанию от куда запускаем 
   table        - название табилцы
   
   сlass        - название класса, если не указан генерируется
                  из названия таблицы, Чтобы указать namespace объекта 
                  и его положение можно написать 
                  /foo/subNamespace/* 
                  при таком занчении сгенерируется класс по этому пути 
                  с с таким namespace токлько вместов * будет название 
                  класса сгенеренное из названия таблица
                  пример: spot2-gen table=table_s1 class=foo\\*
                      - <?php
                        namespace foo;
                        class TableS1 ...
                  
./spot2-gen --table=notice* --class=notice\\models\\*
./spot2-gen --table=sam.table_s1 -dir=models
 
Example:
  ./vendor/bin/spot2-gen --table=gkh.* --class=reformaJkh\\entity\\*
   создаст модели для всех таблиц в схеме gkh в ./reformaJkh/entity/<name>.php
  