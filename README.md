# blog
Create a blog using PHP, MySQL and Ajax.

  Database design:-


  - users table:
  
    group_id [0 => for members, 1 => for admin].
    
    permissions [0 => for admin with limited permissions, 1 => for root].
    
  - articles table.
  
  - categories table.
  
  - comments table.
  
  - likes table.
  
  - views table:

    number_views [number of visited articles].
    
  <img width="566" alt="Database-design" src="https://user-images.githubusercontent.com/112784754/229680961-3dab5281-17b1-4325-851d-6de64892b141.png">
  
  Database analysis:-
  
  <img width="1020" alt="Database-analysis" src="https://user-images.githubusercontent.com/112784754/229680973-b6653cf3-7fcf-431c-b162-362e2b3bcc70.png">
