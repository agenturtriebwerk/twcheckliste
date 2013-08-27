DETAILS:
================================================

Plugin for easy creation of printable checklists. The checkpoints within checklist can be structured in sections and areas. By unchecking areas or sections the printable checklist can be adapted on current demand.


INSTALLATION:
================================================

1. Install the plugin using the Plugin Manager and the download URL above, which points to latest version of the plugin.
2. Unzip the Plugin and move the folder (twcheckliste) in the plugin directory 
2. Configure the plugin via the admin area.


LEGEND:
================================================

- H2 = Section (contains Areas). To adapt checklist before generating print-view
- H3 = Area (contains checkpoints). To adapt checklist before generating print-view
- H4 = Checkpoint
- OL (List item) = Options or free text
- HR (Dividing line) = Fixed distance
- EINGABEFELD = ............
- EINGABEFELDLANG = ........................


EXAMPLE:
================================================
```
<checkliste>

===== Section A =====
==== Area 1 ====
  - Checkpoint 1.1 EINGABEFELDLANG
  - Checkpoint 1.2  EINGABEFELD
  - Checkpoint 1.3
  - Checkpoint 1.4  

==== Area 2 ====
  - Checkpoint 2.1
  - Checkpoint 2.2
  - Checkpoint 2.3

===== Section B =====
==== Area 1 ====
  - EINGABEFELDLANG
  - EINGABEFELD
  - Checkpoint 1.3
  - Checkpoint 1.4  

==== Area 2 ====
  - Checkpoint 2.1
  - Checkpoint 2.2
  - Checkpoint 2.

</checkliste>
```
